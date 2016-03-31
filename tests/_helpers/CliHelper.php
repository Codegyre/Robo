<?php
namespace Codeception\Module;

use Robo\Config;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\NullOutput;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;

class CliHelper extends \Codeception\Module implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    use \Robo\LoadAllTasks {
        task as public;
        taskExec as public;
        taskExecStack as public;
        taskWriteToFile as public;
        taskReplaceInFile as public;
        taskConcat as public;
        taskTmpFile as public;
        taskCleanDir as public;
        taskCopyDir as public;
        taskDeleteDir as public;
        taskFlattenDir as public;
        taskFileSystemStack as public;
        taskGitStack as public;
        taskTmpDir as public;
        _copyDir as public shortcutCopyDir;
        _mirrorDir as public shortcutMirrorDir;
        _tmpDir as public shortcutTmpDir;
        taskPack as public;
        taskExtract as public;
    }

    public function seeDirFound($dir)
    {
        $this->assertTrue(is_dir($dir) && file_exists($dir), "Directory does not exist");
    }

    public function _before(\Codeception\TestCase $test) {
        $this->getModule('Filesystem')->copyDir(codecept_data_dir().'claypit', codecept_data_dir().'sandbox');
        Config::setOutput(new NullOutput());
        $this->setContainer(Config::getContainer());
    }

    public function _after(\Codeception\TestCase $test) {
        $this->getModule('Filesystem')->deleteDir(codecept_data_dir().'sandbox');
        Config::setOutput(new ConsoleOutput());
        chdir(codecept_root_dir());
    }
}
