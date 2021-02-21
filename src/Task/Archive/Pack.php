<?php

namespace Robo\Task\Archive;

use Robo\Contract\PrintedInterface;
use Robo\Result;
use Robo\Task\BaseTask;
use Symfony\Component\Finder\Finder;

/**
 * Creates a zip or tar archive.
 *
 * ``` php
 * <?php
 * $this->taskPack(
 * <archiveFile>)
 * ->add('README')                         // Puts file 'README' in archive at the root
 * ->add('project')                        // Puts entire contents of directory 'project' in archinve inside 'project'
 * ->addFile('dir/file.txt', 'file.txt')   // Takes 'file.txt' from cwd and puts it in archive inside 'dir'.
 * ->run();
 * ?>
 * ```
 */
class Pack extends BaseTask implements PrintedInterface
{
    /**
     * The list of items to be packed into the archive.
     *
     * @var array
     */
    private $items = [];

    /**
     * The full path to the archive to be created.
     *
     * @var string
     */
    private $archiveFile;

    /**
     * Construct the class.
     *
     * @param string $archiveFile
     *   The full path and name of the archive file to create.
     *
     * @since   1.0
     */
    public function __construct($archiveFile)
    {
        $this->archiveFile = $archiveFile;
    }

    /**
     * Satisfy the parent requirement.
     *
     * @return bool
     *   Always returns true.
     *
     * @since   1.0
     */
    public function getPrinted()
    {
        return true;
    }

    /**
     * @param string $archiveFile
     *
     * @return $this
     */
    public function archiveFile($archiveFile)
    {
        $this->archiveFile = $archiveFile;
        return $this;
    }

    /**
     * Add an item to the archive. Like file_exists(), the parameter
     * may be a file or a directory.
     *
     * @param string $placementLocation
     *   Relative path and name of item to store in archive.
     * @param string $filesystemLocation
     *   Absolute or relative path to file or directory's location in filesystem.
     *
     * @return $this
     */
    public function addFile($placementLocation, $filesystemLocation)
    {
        $this->items[$placementLocation] = $filesystemLocation;

        return $this;
    }

    /**
     * Alias for addFile, in case anyone has angst about using
     * addFile with a directory.
     *
     * @param string $placementLocation
     *   Relative path and name of directory to store in archive.
     * @param string $filesystemLocation
     *   Absolute or relative path to directory or directory's location in filesystem.
     *
     * @return $this
     */
    public function addDir($placementLocation, $filesystemLocation)
    {
        $this->addFile($placementLocation, $filesystemLocation);

        return $this;
    }

    /**
     * Add a file or directory, or list of same to the archive.
     *
     * @param string|array $item
     *   If given a string, should contain the relative filesystem path to the
     *   the item to store in archive; this will also be used as the item's
     *   path in the archive, so absolute paths should not be used here.
     *   If given an array, the key of each item should be the path to store
     *   in the archive, and the value should be the filesystem path to the
     *   item to store.
     *
     * @return $this
     */
    public function add($item)
    {
        if (is_array($item)) {
            $this->items = array_merge($this->items, $item);
        } else {
            $this->addFile($item, $item);
        }

        return $this;
    }

    /**
     * Create a zip archive for distribution.
     *
     * @return \Robo\Result
     *
     * @since  1.0
     */
    public function run()
    {
        $this->startTimer();

        // Use the file extension to determine what kind of archive to create.
        $fileInfo = new \SplFileInfo($this->archiveFile);
        $extension = strtolower($fileInfo->getExtension());
        if (empty($extension)) {
            return Result::error($this, "Archive filename must use an extension (e.g. '.zip') to specify the kind of archive to create.");
        }

        try {
            // Inform the user which archive we are creating
            $this->printTaskInfo("Creating archive {filename}", ['filename' => $this->archiveFile]);
            if ($extension == 'zip') {
                $result = $this->archiveZip($this->archiveFile, $this->items);
            } else {
                $result = $this->archiveTar($this->archiveFile, $this->items);
            }
            $this->printTaskSuccess("{filename} created.", ['filename' => $this->archiveFile]);
        } catch (\Exception $e) {
            $this->printTaskError("Could not create {filename}. {exception}", ['filename' => $this->archiveFile, 'exception' => $e->getMessage(), '_style' => ['exception' => '']]);
            $result = Result::error($this, sprintf('Could not create %s. %s', $this->archiveFile, $e->getMessage()));
        }
        $this->stopTimer();
        $result['time'] = $this->getExecutionTime();

        return $result;
    }

    /**
     * @param string $archiveFile
     * @param array $items
     *
     * @return \Robo\Result
     */
    protected function archiveTar($archiveFile, $items)
    {
        if (!class_exists('Archive_Tar')) {
            return Result::errorMissingPackage($this, 'Archive_Tar', 'pear/archive_tar');
        }

        $tar_object = new \Archive_Tar($archiveFile);
        foreach ($items as $placementLocation => $filesystemLocation) {
            $p_remove_dir = $filesystemLocation;
            $p_add_dir = $placementLocation;
            if (is_file($filesystemLocation)) {
                $p_remove_dir = dirname($filesystemLocation);
                $p_add_dir = dirname($placementLocation);
                if (basename($filesystemLocation) != basename($placementLocation)) {
                    return Result::error($this, "Tar archiver does not support renaming files during extraction; could not add $filesystemLocation as $placementLocation.");
                }
            }

            if (!$tar_object->addModify([$filesystemLocation], $p_add_dir, $p_remove_dir)) {
                return Result::error($this, "Could not add $filesystemLocation to the archive.");
            }
        }

        return Result::success($this);
    }

    /**
     * @param string $archiveFile
     * @param array $items
     *
     * @return \Robo\Result
     */
    protected function archiveZip($archiveFile, $items)
    {
        if (!extension_loaded('zlib') || !class_exists(\ZipArchive::class)) {
            return Result::errorMissingExtension($this, 'zlib', 'zip packing');
        }

        $zip = new \ZipArchive($archiveFile, \ZipArchive::CREATE);
        if (!$zip->open($archiveFile, \ZipArchive::CREATE)) {
            return Result::error($this, "Could not create zip archive {$archiveFile}");
        }
        $result = $this->addItemsToZip($zip, $items);
        $zip->close();

        return $result;
    }

    /**
     * @param \ZipArchive $zip
     * @param array $items
     *
     * @return \Robo\Result
     */
    protected function addItemsToZip($zip, $items)
    {
        foreach ($items as $placementLocation => $filesystemLocation) {
            if (is_dir($filesystemLocation)) {
                $finder = new Finder();
                $finder->files()->in($filesystemLocation)->ignoreDotFiles(false);

                foreach ($finder as $file) {
                    // Replace Windows slashes or resulting zip will have issues on *nixes.
                    $relativePathname = str_replace('\\', '/', $file->getRelativePathname());

                    if (!$zip->addFile($file->getRealpath(), "{$placementLocation}/{$relativePathname}")) {
                        return Result::error($this, "Could not add directory $filesystemLocation to the archive; error adding {$file->getRealpath()}.");
                    }
                    // This will retain the file permissions for *nix systems
                    // Source: https://github.com/consolidation/Robo/pull/888
                    if (stripos(PHP_OS, 'WIN') !== 0) {
                        $zip->setExternalAttributesName(
                            "{$placementLocation}/{$relativePathname}",
                            \ZipArchive::OPSYS_UNIX,
                            $file->getPerms() << 16
                        );
                    } else {
                        // For windows we force 0644 for all the files
                        // Because it seems when the archive is created on Windows the files by default has
                        // 0666 permission (can be seen when unpacked on a *nix system)
                        $zip->setExternalAttributesName(
                            "{$placementLocation}/{$relativePathname}",
                            \ZipArchive::OPSYS_WINDOWS_NTFS,
                            33188 // Is the decimal for 100644
                        );
                    }
                }
            } elseif (is_file($filesystemLocation)) {
                if (!$zip->addFile($filesystemLocation, $placementLocation)) {
                    return Result::error($this, "Could not add file $filesystemLocation to the archive.");
                }
                if (stripos(PHP_OS, 'WIN') !== 0) {
                    $zip->setExternalAttributesName(
                        $placementLocation,
                        \ZipArchive::OPSYS_UNIX,
                        fileperms($filesystemLocation) << 16
                    );
                } else {
                    $zip->setExternalAttributesName(
                        $placementLocation,
                        \ZipArchive::OPSYS_WINDOWS_NTFS,
                        33188
                    );
                }
            } else {
                return Result::error($this, "Could not find $filesystemLocation for the archive.");
            }
        }

        return Result::success($this);
    }
}
