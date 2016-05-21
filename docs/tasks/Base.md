# Base Tasks
## Exec


Executes shell script. Closes it when running in background mode.

``` php
<?php
$this->taskExec('compass')->arg('watch')->run();
// or use shortcut
$this->_exec('compass watch');

$this->taskExec('compass watch')->background()->run();

if ($this->taskExec('phpunit .')->run()->wasSuccessful()) {
 $this->say('tests passed');
}

?>
```

* `background()`  Executes command in background mode (asynchronously)
* `timeout($timeout)`  Stop command if it runs longer then $timeout in seconds
* `idleTimeout($timeout)`  Stops command if it does not output something for a while
* `env(array $env)`  Sets the environment variables for the command
* `simulate($context)`  Called in place of `run()` for simulated tasks.
* `setLogger($logger)`  Sets a logger.
* `setContainer($container)`  Set a container.
* `getContainer()`  Get the container.
* `logger()` 
* `addToCollection($collection, $taskName = null, $rollbackTask = null)` 
* `addAsRollback($collection)` 
* `addAsCompletion($collection)` 
* `addToCollectionAndIgnoreErrors($collection, $taskName = null)` 
* `dir($dir)`  changes working directory of command
* `printed($arg)`  Should command output be printed
* `arg($arg)`  Pass argument to executable
* `args($args)`  Pass methods parameters as arguments to executable
* `option($option, $value = null)`  Pass option to executable. Options are prefixed with `--` , value can be provided in second parameter.
* `optionList($option, $value = null)`  Pass multiple options to executable. Value can be a string or array.

## ExecStack


Execute commands one by one in stack.
Stack can be stopped on first fail if you call `stopOnFail()`.

```php
<?php
$this->taskExecStack()
 ->stopOnFail()
 ->exec('mkdir site')
 ->exec('cd site')
 ->env(['APP_ENV' => 'dev'])
 ->exec('gulp build')
 ->run();

?>
```

* `$this stopOnFail()` 

* `executable($executable)` 
* `exec($command)` 
* `stopOnFail($stopOnFail = null)` 
* `result($result)` 
* `setLogger($logger)`  Sets a logger.
* `setContainer($container)`  Set a container.
* `getContainer()`  Get the container.
* `logger()` 
* `addToCollection($collection, $taskName = null, $rollbackTask = null)` 
* `addAsRollback($collection)` 
* `addAsCompletion($collection)` 
* `addToCollectionAndIgnoreErrors($collection, $taskName = null)` 
* `env(array $env)`  Sets the environment variables for the command
* `dir($dir)`  changes working directory of command
* `printed($arg)`  Should command output be printed

## ParallelExec


Class ParallelExecTask

``` php
<?php
$this->taskParallelExec()
  ->process('php ~/demos/script.php hey')
  ->process('php ~/demos/script.php hoy')
  ->process('php ~/demos/script.php gou')
  ->run();
?>
```


* ` timeout(int $timeout)`  stops process if it runs longer then `$timeout` (seconds)
* ` idleTimeout(int $timeout)`  stops process if it does not output for time longer then `$timeout` (seconds)

* `printed($isPrinted = null)` 
* `process($command)` 
* `timeout($timeout)` 
* `idleTimeout($idleTimeout)` 
* `setLogger($logger)`  Sets a logger.
* `setContainer($container)`  Set a container.
* `getContainer()`  Get the container.
* `logger()` 
* `addToCollection($collection, $taskName = null, $rollbackTask = null)` 
* `addAsRollback($collection)` 
* `addAsCompletion($collection)` 
* `addToCollectionAndIgnoreErrors($collection, $taskName = null)` 
* `setProgressIndicator($progressIndicator)` 
* `inProgress()` 
* `startProgressIndicator($totalSteps = null)` 
* `stopProgressIndicator()` 
* `hideProgressIndicator()` 
* `showProgressIndicator()` 
* `advanceProgressIndicator($steps = null)` 

## SymfonyCommand


Executes Symfony Command

``` php
<?php
// Symfony Command
$this->taskSymfonyCommand(new \Codeception\Command\Run('run'))
     ->arg('suite','acceptance')
     ->opt('debug')
     ->run();

// Artisan Command
$this->taskSymfonyCommand(new ModelGeneratorCommand())
     ->arg('name', 'User')
     ->run();
?>
```

* `arg($arg, $value)` 
* `opt($option, $value = null)` 
* `setLogger($logger)`  Sets a logger.
* `setContainer($container)`  Set a container.
* `getContainer()`  Get the container.
* `logger()` 
* `addToCollection($collection, $taskName = null, $rollbackTask = null)` 
* `addAsRollback($collection)` 
* `addAsCompletion($collection)` 
* `addToCollectionAndIgnoreErrors($collection, $taskName = null)` 

## Watch


Runs task when specified file or dir was changed.
Uses Lurker library.

``` php
<?php
$this->taskWatch()
 ->monitor('composer.json', function() {
     $this->taskComposerUpdate()->run();
})->monitor('src', function() {
     $this->taskExec('phpunit')->run();
})->run();
?>
```

* `monitor($paths, $callable)` 
* `setLogger($logger)`  Sets a logger.
* `setContainer($container)`  Set a container.
* `getContainer()`  Get the container.
* `logger()` 
* `addToCollection($collection, $taskName = null, $rollbackTask = null)` 
* `addAsRollback($collection)` 
* `addAsCompletion($collection)` 
* `addToCollectionAndIgnoreErrors($collection, $taskName = null)` 

