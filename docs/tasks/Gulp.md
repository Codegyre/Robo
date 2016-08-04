# Gulp Tasks

## Run


Gulp Run

``` php
<?php
// simple execution
$this->taskGulpRun()->run();

// run task 'clean' with --silent option
$this->taskGulpRun('clean')
     ->silent()
     ->run();
?>
```

* `silent()`  adds `silent` option to gulp
* `noColor()`  adds `--no-color` option to gulp
* `color()`  adds `--color` option to gulp
* `simple()`  adds `--tasks-simple` option to gulp
* `injectDependencies($child)`  {inheritdoc}
* `logger()` 
* `setLogger($logger)`  Sets a logger.
* `progressIndicatorSteps()` 
* `setProgressIndicator($progressIndicator)` 
* `setConfig($config)`  Set the config management object.
* `getConfig()`  Get the config management object.
* `inflect($parent)`  Ask the provided parent class to inject all of the dependencies
* `dir($dir)`  changes working directory of command
* `printed($arg)`  Should command output be printed
* `arg($arg)`  Pass argument to executable
* `args($args)`  Pass methods parameters as arguments to executable
* `option($option, $value = null)`  Pass option to executable. Options are prefixed with `--` , value can be provided in second parameter.
* `optionList($option, $value = null)`  Pass multiple options to executable. Value can be a string or array.

