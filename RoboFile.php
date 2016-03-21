<?php
use Symfony\Component\Finder\Finder;

class RoboFile extends \Robo\Tasks
{
    // Example:
    // ./robo wrap 'Symfony\Component\Filesystem\Filesystem' FilesystemStack
    public function wrap($className, $wrapperClassName = "")
    {
        $delegate = new ReflectionClass($className);

        $leadingCommentChars = " * ";
        $methodDescriptions = [];
        $methodImplementations = [];
        $immediateMethods = [];
        foreach ($delegate->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $methodName = $method->getName();
            $getter = preg_match('/^(get|has|is)/', $methodName);
            $setter = preg_match('/^(set|unset)/', $methodName);
            $argPrototypeList = [];
            $argNameList = [];
            $needsImplementation = false;
            foreach ($method->getParameters() as $arg) {
                $argDescription = '$' . $arg->name;
                $argNameList[] = $argDescription;
                if ($arg->isOptional()) {
                    $argDescription = $argDescription . ' = ' . str_replace("\n", "", var_export($arg->getDefaultValue(), true));
                    // We will create wrapper methods for any method that
                    // has default parameters.
                    $needsImplementation = true;
                }
                $argPrototypeList[] = $argDescription;
            }
            $argPrototypeString = implode(', ', $argPrototypeList);
            $argNameListString = implode(', ', $argNameList);

            if ($methodName[0] != '_') {
                $methodDescriptions[] = "@method $methodName($argPrototypeString)";

                if ($getter) {
                    $immediateMethods[] = "    public function $methodName($argPrototypeString)\n    {\n        return \$this->delegate->$methodName($argNameListString);\n    }";
                } elseif ($setter) {
                    $immediateMethods[] = "    public function $methodName($argPrototypeString)\n    {\n        \$this->delegate->$methodName($argNameListString);\n        return \$this;\n    }";
                } elseif ($needsImplementation) {
                    // Include an implementation for the wrapper method if necessary
                    $methodImplementations[] = "    protected function _$methodName($argPrototypeString)\n    {\n        \$this->delegate->$methodName($argNameListString);\n    }";
                }
            }
        }

        $classNameParts = explode('\\', $className);
        $delegate = array_pop($classNameParts);
        $delegateNamespace = implode('\\', $classNameParts);

        if (empty($wrapperClassName)) {
            $wrapperClassName = $delegate;
        }

        $replacements['{delegateNamespace}'] = $delegateNamespace;
        $replacements['{delegate}'] = $delegate;
        $replacements['{wrapperClassName}'] = $wrapperClassName;
        $replacements['{taskname}'] = "task$delegate";
        $replacements['{methodList}'] = $leadingCommentChars . implode("\n$leadingCommentChars", $methodDescriptions);
        $replacements['{immediateMethods}'] = "\n\n" . implode("\n\n", $immediateMethods);
        $replacements['{methodImplementations}'] = "\n\n" . implode("\n\n", $methodImplementations);

        $template = file_get_contents(__DIR__ . "/GeneratedWrapper.tmpl");
        $template = str_replace(array_keys($replacements), array_values($replacements), $template);

        print $template;
    }

    public function release()
    {
        $this->yell("Releasing Robo");

        $this->docs();
        $this->taskGitStack()
            ->add('-A')
            ->commit("auto-update")
            ->pull()
            ->push()
            ->run();

        $this->pharPublish();
        $this->publish();

        $this->taskGitHubRelease(\Robo\Runner::VERSION)
            ->uri('Codegyre/Robo')
            ->askDescription($this)
            ->run();

        $this->versionBump();
    }

    public function test($args = "")
    {
        return $this->taskCodecept()
            ->args($args)
            ->run();
    }

    public function changed($addition)
    {
        $this->taskChangelog()
            ->version(\Robo\Runner::VERSION)
            ->change($addition)
            ->run();
    }

    public function versionBump($version = null)
    {
        if (!$version) {
            $versionParts = explode('.', \Robo\Runner::VERSION);
            $versionParts[count($versionParts)-1]++;
            $version = implode('.', $versionParts);
        }
        $this->taskReplaceInFile(__DIR__.'/src/Runner.php')
            ->from("VERSION = '".\Robo\Runner::VERSION."'")
            ->to("VERSION = '".$version."'")
            ->run();
    }

    /**
     * generate docs
     */
    public function docs()
    {
        $collection = $this->collection();
        $files = Finder::create()->files()->name('*.php')->in('src/Task');
        $docs = [];
        foreach ($files as $file) {
            if ($file->getFileName() == 'loadTasks.php') {
                continue;
            }
            if ($file->getFileName() == 'loadShortcuts.php') {
                continue;
            }
            $ns = $file->getRelativePath();
            if (!$ns) {
                continue;
            }
            $class = basename(substr($file, 0, -4));
            class_exists($class = "Robo\\Task\\$ns\\$class");
            $docs[$ns][] = $class;
        }
        ksort($docs);

        foreach ($docs as $ns => $tasks) {
            $taskGenerator = $this->taskGenDoc("docs/tasks/$ns.md");
            $taskGenerator->filterClasses(function (\ReflectionClass $r) {
                return !($r->isAbstract() or $r->isTrait()) and $r->implementsInterface('Robo\Contract\TaskInterface');
            })->prepend("# $ns Tasks");
            sort($tasks);
            foreach ($tasks as $class) {
                $taskGenerator->docClass($class);
            }

            $taskGenerator->filterMethods(
                function (\ReflectionMethod $m) {
                    if ($m->isConstructor() or $m->isDestructor() or $m->isStatic()) {
                        return false;
                    }
                    return !in_array($m->name, ['run', '', '__call', 'getCommand', 'getPrinted']) and $m->isPublic(); // methods are not documented
                }
            )->processClassSignature(
                function ($c) {
                    return "## " . preg_replace('~Task$~', '', $c->getShortName()) . "\n";
                }
            )->processClassDocBlock(
                function (\ReflectionClass $c, $doc) {
                    $doc = preg_replace('~@method .*?(.*?)\)~', '* `$1)` ', $doc);
                    $doc = str_replace('\\'.$c->getName(), '', $doc);
                    return $doc;
                }
            )->processMethodSignature(
                function (\ReflectionMethod $m, $text) {
                    return str_replace('#### *public* ', '* `', $text) . '`';
                }
            )->processMethodDocBlock(
                function (\ReflectionMethod $m, $text) {

                    return $text ? ' ' . trim(strtok($text, "\n"), "\n") : '';
                }
            )->addToCollection($collection);
        }
        $collection->run();
    }

    /**
     * Builds a site in gh-pages branch. Uses mkdocs
     */
    public function publish()
    {
        $current_branch = exec('git rev-parse --abbrev-ref HEAD');

        $collection = $this->collection();
        $this->taskGitStack()
            ->checkout('site')
            ->merge('master')
            ->addToCollection($collection);
        $this->taskGitStack()
            ->checkout($current_branch)
            ->addAsCompletion($collection);
        $this->taskFilesystemStack()
            ->copy('CHANGELOG.md', 'docs/changelog.md')
            ->addToCollection($collection);
        $this->taskFilesystemStack()
            ->remove('docs/changelog.md')
            ->addAsCompletion($collection);
        $this->taskExec('mkdocs gh-deploy')
            ->addToCollection($collection);
        $collection->run();
    }

    public function pharBuild()
    {
        $collection = $this->collection();

        $this->taskComposerInstall()
            ->noDev()
            ->printed(false)
            ->addToCollection($collection);

        $packer = $this->taskPackPhar('robo.phar');
        $files = Finder::create()->ignoreVCS(true)
            ->files()
            ->name('*.php')
            ->path('src')
            ->path('vendor')
            ->exclude('symfony/config/Tests')
            ->exclude('symfony/console/Tests')
            ->exclude('symfony/event-dispatcher/Tests')
            ->exclude('symfony/filesystem/Tests')
            ->exclude('symfony/finder/Tests')
            ->exclude('symfony/process/Tests')
            ->exclude('henrikbjorn/lurker/tests')
            ->in(__DIR__);
        foreach ($files as $file) {
            $packer->addFile($file->getRelativePathname(), $file->getRealPath());
        }
        $packer->addFile('robo', 'robo')
            ->executable('robo')
            ->addToCollection($collection);

        $this->taskComposerInstall()
            ->printed(false)
            ->addToCollection($collection);

        $collection->run();
    }

    public function pharInstall()
    {
        $this->taskExec('sudo cp')
            ->arg('robo.phar')
            ->arg('/usr/bin/robo')
            ->run();
    }

    public function pharPublish()
    {
        $this->pharBuild();

        $this->_rename('robo.phar', 'robo-release.phar');
        $this->taskGitStack()->checkout('gh-pages')->run();
        $this->taskFilesystemStack()
            ->remove('robo.phar')
            ->rename('robo-release.phar', 'robo.phar')
            ->run();
        $this->taskGitStack()
            ->add('robo.phar')
            ->commit('robo.phar published')
            ->push('origin', 'gh-pages')
            ->checkout('master')
            ->run();
    }

    public function tryWatch()
    {
        $this->taskWatch()->monitor(['composer.json', 'composer.lock'], function () {
            $this->taskComposerUpdate()->run();
        })->run();
    }

    public function tryInput()
    {
        $answer = $this->ask('how are you?');
        $this->say('You are '.$answer);
        $yes = $this->confirm('Do you want one more question?');
        if (!$yes) {
            return;
        }
        $lang = $this->askDefault('what is your favorite scripting language?', 'PHP');
        $this->say($lang);
        $pin = $this->askHidden('Ok, now tell your PIN code (it is hidden)');
        $this->yell('Ha-ha, your pin code is: '.$pin);
        $this->say('Bye!');
    }

    /**
     * Test parallel execution
     */
    public function tryPara()
    {
        $this->taskParallelExec()
            ->process('php ~/demos/robotests/parascript.php hey')
            ->process('php ~/demos/robotests/parascript.php hoy')
            ->process('php ~/demos/robotests/parascript.php gou')
            ->process('php ~/demos/robotests/parascript.php die')
            ->run();
    }

    public function tryOptbool($opts = ['silent|s' => false])
    {
        if (!$opts['silent']) {
            $this->say("Hello, world");
        }
    }

    public function tryServer()
    {
        $this->taskServer(8000)
            ->dir('site')
            ->arg('site/index.php')
            ->run();
    }

    public function tryOpenBrowser()
    {
        $this->taskOpenBrowser([
            'http://robo.li',
            'https://github.com/Codegyre/Robo'
            ])
            ->run();
    }

    public function tryInteractive()
    {
        new SomeTask();
        $this->_exec('php -r "echo php_sapi_name();"');
    }

    public function tryError()
    {
        $result = $this->taskExec('ls xyzzy' . date('U'))->dir('/tmp')->run();
    }

    public function trySuccess()
    {
        $result = $this->taskExec('pwd')->run();
    }

    /**
     * Run the PHP Codesniffer on a file or directory.
     *
     * @param string $file
     *    A file or directory to analyze.
     * @option $autofix Whether to run the automatic fixer or not.
     * @option $strict Show warnings as well as errors.
     *    Default is to show only errors.
     */
    public function sniff(
        $file = 'src/',
        $options = [
            'autofix' => false,
            'strict' => false,
        ]
    ) {
        $strict = $options['strict'] ? '' : '-n';
        $result = $this->taskExec("./vendor/bin/phpcs --standard=PSR2 {$strict} {$file}")->run();
        if (!$result->wasSuccessful()) {
            if (!$options['autofix']) {
                $options['autofix'] = $this->confirm('Would you like to run phpcbf to fix the reported errors?');
            }
            if ($options['autofix']) {
                $this->taskExec("./vendor/bin/phpcbf --standard=PSR2 {$file}")->run();
            }
        }
    }

    public function tryDeprecated()
    {
        $result = (new \Robo\Task\Base\Exec('pwd'))->run();
    }

    public function tryTmpDir()
    {
        // Set up a collection to add tasks to
        $collection = $this->collection();

        // Get a temporary directory to work in. Note that we get a
        // name back, but the directory is not created until the task
        // runs.  This technically is not thread-safe, but we create
        // a random name, so it is unlikely to conflict.
        $tmpPath = $this->taskTmpDir()
            ->addToCollection($collection)
            ->getPath();

        // We can create the temporary directory early by running
        // 'runWithoutCompletion()'.  n.b. if we called 'run()' at
        // this point, the collection's 'complete()' method would be
        // called, and the temporary directory would be deleted.
        $mktmpResult = $collection->runWithoutCompletion();

        if (is_dir($tmpPath)) {
            $this->say("Created a temporary directory at $tmpPath");
        } else {
            $this->say("Requested a temporary directory at $tmpPath, but it was not created");
        }

        // Run the task collection
        $result = $collection->run();

        if (is_dir($tmpPath)) {
            $this->say("The temporary directory at $tmpPath was not cleaned up after the collection completed.");
        } else {
            $this->say("The temporary directory at $tmpPath was automatically deleted.");
        }
    }
}
