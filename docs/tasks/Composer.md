# Composer Tasks

## CheckPlatformReqs


Composer Check Platform Requirements

``` php
<?php
// simple execution
$this->taskComposerValidate()->run();
?>
```

* `preferDist($preferDist = null)`  adds `prefer-dist` option to composer
* `preferSource()`  adds `prefer-source` option to composer
* `dev($dev = null)`  adds `dev` option to composer
* `noDev()`  adds `no-dev` option to composer
* `ansi($ansi = null)`  adds `ansi` option to composer
* `noAnsi()`  adds `no-ansi` option to composer
* `interaction($interaction = null)`   * `param bool` $interaction
* `noInteraction()`  adds `no-interaction` option to composer
* `optimizeAutoloader($optimize = null)`  adds `optimize-autoloader` option to composer
* `ignorePlatformRequirements($ignore = null)`  adds `ignore-platform-reqs` option to composer
* `disablePlugins($disable = null)`  disable plugins
* `noScripts($disable = null)`  skip scripts
* `workingDir($dir)`  adds `--working-dir $dir` option to composer
* `buildCommand()`  Copy class fields into command options as directed.
* `setOutput($output)`  Sets the Console Output.
* `dir($dir)`  Changes working directory of command
* `arg($arg)`  Pass argument to executable. Its value will be automatically escaped.
* `args($args)`  Pass methods parameters as arguments to executable. Argument values
* `rawArg($arg)`  Pass the provided string in its raw (as provided) form as an argument to executable.
* `option($option, $value = null, $separator = null)`  Pass option to executable. Options are prefixed with `--` , value can be provided in second parameter.
* `options(array $options, $separator = null)`  Pass multiple options to executable. The associative array contains
* `optionList($option, $value = null, $separator = null)`  Pass an option with multiple values to executable. Value can be a string or array.

## Config


Composer Config

``` php
<?php
// simple execution
$this->taskComposerConfig()->set('bin-dir', 'bin/')->run();
?>
```

* `set($key, $value)`  Set a configuration value.
* `useGlobal($useGlobal = null)`  Operate on the global repository
* `repository($id, $uri, $repoType = null)`   * `param string` $id
* `removeRepository($id)`   * `param string` $id
* `disableRepository($id)`   * `param string` $id
* `enableRepository($id)`   * `param string` $id
* `preferDist($preferDist = null)`  adds `prefer-dist` option to composer
* `preferSource()`  adds `prefer-source` option to composer
* `dev($dev = null)`  adds `dev` option to composer
* `noDev()`  adds `no-dev` option to composer
* `ansi($ansi = null)`  adds `ansi` option to composer
* `noAnsi()`  adds `no-ansi` option to composer
* `interaction($interaction = null)`   * `param bool` $interaction
* `noInteraction()`  adds `no-interaction` option to composer
* `optimizeAutoloader($optimize = null)`  adds `optimize-autoloader` option to composer
* `ignorePlatformRequirements($ignore = null)`  adds `ignore-platform-reqs` option to composer
* `disablePlugins($disable = null)`  disable plugins
* `noScripts($disable = null)`  skip scripts
* `workingDir($dir)`  adds `--working-dir $dir` option to composer
* `buildCommand()`  Copy class fields into command options as directed.
* `setOutput($output)`  Sets the Console Output.
* `dir($dir)`  Changes working directory of command
* `arg($arg)`  Pass argument to executable. Its value will be automatically escaped.
* `args($args)`  Pass methods parameters as arguments to executable. Argument values
* `rawArg($arg)`  Pass the provided string in its raw (as provided) form as an argument to executable.
* `option($option, $value = null, $separator = null)`  Pass option to executable. Options are prefixed with `--` , value can be provided in second parameter.
* `options(array $options, $separator = null)`  Pass multiple options to executable. The associative array contains
* `optionList($option, $value = null, $separator = null)`  Pass an option with multiple values to executable. Value can be a string or array.

## CreateProject


Composer CreateProject

``` php
<?php
// simple execution
$this->taskComposerCreateProject()->source('foo/bar')->target('myBar')->run();
?>
```

* `source($source)`   * `param string` $source
* `target($target)`   * `param string` $target
* `version($version)`   * `param string` $version
* `keepVcs($keep = null)`   * `param bool` $keep
* `noInstall($noInstall = null)`   * `param bool` $noInstall
* `repository($repository)`   * `param string` $repository
* `stability($stability)`   * `param string` $stability
* `buildCommand()`  {@inheritdoc}
* `preferDist($preferDist = null)`  adds `prefer-dist` option to composer
* `preferSource()`  adds `prefer-source` option to composer
* `dev($dev = null)`  adds `dev` option to composer
* `noDev()`  adds `no-dev` option to composer
* `ansi($ansi = null)`  adds `ansi` option to composer
* `noAnsi()`  adds `no-ansi` option to composer
* `interaction($interaction = null)`   * `param bool` $interaction
* `noInteraction()`  adds `no-interaction` option to composer
* `optimizeAutoloader($optimize = null)`  adds `optimize-autoloader` option to composer
* `ignorePlatformRequirements($ignore = null)`  adds `ignore-platform-reqs` option to composer
* `disablePlugins($disable = null)`  disable plugins
* `noScripts($disable = null)`  skip scripts
* `workingDir($dir)`  adds `--working-dir $dir` option to composer
* `setOutput($output)`  Sets the Console Output.
* `dir($dir)`  Changes working directory of command
* `arg($arg)`  Pass argument to executable. Its value will be automatically escaped.
* `args($args)`  Pass methods parameters as arguments to executable. Argument values
* `rawArg($arg)`  Pass the provided string in its raw (as provided) form as an argument to executable.
* `option($option, $value = null, $separator = null)`  Pass option to executable. Options are prefixed with `--` , value can be provided in second parameter.
* `options(array $options, $separator = null)`  Pass multiple options to executable. The associative array contains
* `optionList($option, $value = null, $separator = null)`  Pass an option with multiple values to executable. Value can be a string or array.

## DumpAutoload


Composer Dump Autoload

``` php
<?php
// simple execution
$this->taskComposerDumpAutoload()->run();

// dump auto loader with custom path
$this->taskComposerDumpAutoload('path/to/my/composer.phar')
     ->preferDist()
     ->run();

// optimize autoloader dump with custom path
$this->taskComposerDumpAutoload('path/to/my/composer.phar')
     ->optimize()
     ->run();

// optimize autoloader dump with custom path and no dev
$this->taskComposerDumpAutoload('path/to/my/composer.phar')
     ->optimize()
     ->noDev()
     ->run();
?>
```

* `optimize($optimize = null)`   * `param bool` $optimize
* `preferDist($preferDist = null)`  adds `prefer-dist` option to composer
* `preferSource()`  adds `prefer-source` option to composer
* `dev($dev = null)`  adds `dev` option to composer
* `noDev()`  adds `no-dev` option to composer
* `ansi($ansi = null)`  adds `ansi` option to composer
* `noAnsi()`  adds `no-ansi` option to composer
* `interaction($interaction = null)`   * `param bool` $interaction
* `noInteraction()`  adds `no-interaction` option to composer
* `optimizeAutoloader($optimize = null)`  adds `optimize-autoloader` option to composer
* `ignorePlatformRequirements($ignore = null)`  adds `ignore-platform-reqs` option to composer
* `disablePlugins($disable = null)`  disable plugins
* `noScripts($disable = null)`  skip scripts
* `workingDir($dir)`  adds `--working-dir $dir` option to composer
* `buildCommand()`  Copy class fields into command options as directed.
* `setOutput($output)`  Sets the Console Output.
* `dir($dir)`  Changes working directory of command
* `arg($arg)`  Pass argument to executable. Its value will be automatically escaped.
* `args($args)`  Pass methods parameters as arguments to executable. Argument values
* `rawArg($arg)`  Pass the provided string in its raw (as provided) form as an argument to executable.
* `option($option, $value = null, $separator = null)`  Pass option to executable. Options are prefixed with `--` , value can be provided in second parameter.
* `options(array $options, $separator = null)`  Pass multiple options to executable. The associative array contains
* `optionList($option, $value = null, $separator = null)`  Pass an option with multiple values to executable. Value can be a string or array.

## Init


Composer Init

``` php
<?php
// simple execution
$this->taskComposerInit()->run();
?>
```

* `projectName($projectName)`   * `param string` $projectName
* `description($description)`   * `param string` $description
* `author($author)`   * `param string` $author
* `projectType($type)`   * `param string` $type
* `homepage($homepage)`   * `param string` $homepage
* `dependency($project, $version = null)`  'require' is a keyword, so it cannot be a method name.
* `stability($stability)`   * `param string` $stability
* `license($license)`   * `param string` $license
* `repository($repository)`   * `param string` $repository
* `preferDist($preferDist = null)`  adds `prefer-dist` option to composer
* `preferSource()`  adds `prefer-source` option to composer
* `dev($dev = null)`  adds `dev` option to composer
* `noDev()`  adds `no-dev` option to composer
* `ansi($ansi = null)`  adds `ansi` option to composer
* `noAnsi()`  adds `no-ansi` option to composer
* `interaction($interaction = null)`   * `param bool` $interaction
* `noInteraction()`  adds `no-interaction` option to composer
* `optimizeAutoloader($optimize = null)`  adds `optimize-autoloader` option to composer
* `ignorePlatformRequirements($ignore = null)`  adds `ignore-platform-reqs` option to composer
* `disablePlugins($disable = null)`  disable plugins
* `noScripts($disable = null)`  skip scripts
* `workingDir($dir)`  adds `--working-dir $dir` option to composer
* `buildCommand()`  Copy class fields into command options as directed.
* `setOutput($output)`  Sets the Console Output.
* `dir($dir)`  Changes working directory of command
* `arg($arg)`  Pass argument to executable. Its value will be automatically escaped.
* `args($args)`  Pass methods parameters as arguments to executable. Argument values
* `rawArg($arg)`  Pass the provided string in its raw (as provided) form as an argument to executable.
* `option($option, $value = null, $separator = null)`  Pass option to executable. Options are prefixed with `--` , value can be provided in second parameter.
* `options(array $options, $separator = null)`  Pass multiple options to executable. The associative array contains
* `optionList($option, $value = null, $separator = null)`  Pass an option with multiple values to executable. Value can be a string or array.

## Install


Composer Install

``` php
<?php
// simple execution
$this->taskComposerInstall()->run();

// prefer dist with custom path
$this->taskComposerInstall('path/to/my/composer.phar')
     ->preferDist()
     ->run();

// optimize autoloader with custom path
$this->taskComposerInstall('path/to/my/composer.phar')
     ->optimizeAutoloader()
     ->run();
?>
```

* `noSuggest($noSuggest = null)`  adds `no-suggest` option to composer
* `preferDist($preferDist = null)`  adds `prefer-dist` option to composer
* `preferSource()`  adds `prefer-source` option to composer
* `dev($dev = null)`  adds `dev` option to composer
* `noDev()`  adds `no-dev` option to composer
* `ansi($ansi = null)`  adds `ansi` option to composer
* `noAnsi()`  adds `no-ansi` option to composer
* `interaction($interaction = null)`   * `param bool` $interaction
* `noInteraction()`  adds `no-interaction` option to composer
* `optimizeAutoloader($optimize = null)`  adds `optimize-autoloader` option to composer
* `ignorePlatformRequirements($ignore = null)`  adds `ignore-platform-reqs` option to composer
* `disablePlugins($disable = null)`  disable plugins
* `noScripts($disable = null)`  skip scripts
* `workingDir($dir)`  adds `--working-dir $dir` option to composer
* `buildCommand()`  Copy class fields into command options as directed.
* `setOutput($output)`  Sets the Console Output.
* `dir($dir)`  Changes working directory of command
* `arg($arg)`  Pass argument to executable. Its value will be automatically escaped.
* `args($args)`  Pass methods parameters as arguments to executable. Argument values
* `rawArg($arg)`  Pass the provided string in its raw (as provided) form as an argument to executable.
* `option($option, $value = null, $separator = null)`  Pass option to executable. Options are prefixed with `--` , value can be provided in second parameter.
* `options(array $options, $separator = null)`  Pass multiple options to executable. The associative array contains
* `optionList($option, $value = null, $separator = null)`  Pass an option with multiple values to executable. Value can be a string or array.

## Remove


Composer Remove

``` php
<?php
// simple execution
$this->taskComposerRemove()->run();
?>
```

* `dev($dev = null)`   * `param bool` $dev
* `noProgress($noProgress = null)`   * `param bool` $noProgress
* `noUpdate($noUpdate = null)`   * `param bool` $noUpdate
* `updateNoDev($updateNoDev = null)`   * `param bool` $updateNoDev
* `noUpdateWithDependencies($updateWithDependencies = null)`   * `param bool` $updateWithDependencies
* `preferDist($preferDist = null)`  adds `prefer-dist` option to composer
* `preferSource()`  adds `prefer-source` option to composer
* `noDev()`  adds `no-dev` option to composer
* `ansi($ansi = null)`  adds `ansi` option to composer
* `noAnsi()`  adds `no-ansi` option to composer
* `interaction($interaction = null)`   * `param bool` $interaction
* `noInteraction()`  adds `no-interaction` option to composer
* `optimizeAutoloader($optimize = null)`  adds `optimize-autoloader` option to composer
* `ignorePlatformRequirements($ignore = null)`  adds `ignore-platform-reqs` option to composer
* `disablePlugins($disable = null)`  disable plugins
* `noScripts($disable = null)`  skip scripts
* `workingDir($dir)`  adds `--working-dir $dir` option to composer
* `buildCommand()`  Copy class fields into command options as directed.
* `setOutput($output)`  Sets the Console Output.
* `dir($dir)`  Changes working directory of command
* `arg($arg)`  Pass argument to executable. Its value will be automatically escaped.
* `args($args)`  Pass methods parameters as arguments to executable. Argument values
* `rawArg($arg)`  Pass the provided string in its raw (as provided) form as an argument to executable.
* `option($option, $value = null, $separator = null)`  Pass option to executable. Options are prefixed with `--` , value can be provided in second parameter.
* `options(array $options, $separator = null)`  Pass multiple options to executable. The associative array contains
* `optionList($option, $value = null, $separator = null)`  Pass an option with multiple values to executable. Value can be a string or array.

## RequireDependency


Composer Require

``` php
<?php
// simple execution
$this->taskComposerRequire()->dependency('foo/bar', '^.2.4.8')->run();
?>
```

* `dependency($project, $version = null)`  'require' is a keyword, so it cannot be a method name.
* `noSuggest($noSuggest = null)`  adds `no-suggest` option to composer
* `preferDist($preferDist = null)`  adds `prefer-dist` option to composer
* `preferSource()`  adds `prefer-source` option to composer
* `dev($dev = null)`  adds `dev` option to composer
* `noDev()`  adds `no-dev` option to composer
* `ansi($ansi = null)`  adds `ansi` option to composer
* `noAnsi()`  adds `no-ansi` option to composer
* `interaction($interaction = null)`   * `param bool` $interaction
* `noInteraction()`  adds `no-interaction` option to composer
* `optimizeAutoloader($optimize = null)`  adds `optimize-autoloader` option to composer
* `ignorePlatformRequirements($ignore = null)`  adds `ignore-platform-reqs` option to composer
* `disablePlugins($disable = null)`  disable plugins
* `noScripts($disable = null)`  skip scripts
* `workingDir($dir)`  adds `--working-dir $dir` option to composer
* `buildCommand()`  Copy class fields into command options as directed.
* `setOutput($output)`  Sets the Console Output.
* `dir($dir)`  Changes working directory of command
* `arg($arg)`  Pass argument to executable. Its value will be automatically escaped.
* `args($args)`  Pass methods parameters as arguments to executable. Argument values
* `rawArg($arg)`  Pass the provided string in its raw (as provided) form as an argument to executable.
* `option($option, $value = null, $separator = null)`  Pass option to executable. Options are prefixed with `--` , value can be provided in second parameter.
* `options(array $options, $separator = null)`  Pass multiple options to executable. The associative array contains
* `optionList($option, $value = null, $separator = null)`  Pass an option with multiple values to executable. Value can be a string or array.


## Update


Composer Update

``` php
<?php
// simple execution
$this->taskComposerUpdate()->run();

// prefer dist with custom path
$this->taskComposerUpdate('path/to/my/composer.phar')
     ->preferDist()
     ->run();

// optimize autoloader with custom path
$this->taskComposerUpdate('path/to/my/composer.phar')
     ->optimizeAutoloader()
     ->run();
?>
```

* `noSuggest($noSuggest = null)`  adds `no-suggest` option to composer
* `preferDist($preferDist = null)`  adds `prefer-dist` option to composer
* `preferSource()`  adds `prefer-source` option to composer
* `dev($dev = null)`  adds `dev` option to composer
* `noDev()`  adds `no-dev` option to composer
* `ansi($ansi = null)`  adds `ansi` option to composer
* `noAnsi()`  adds `no-ansi` option to composer
* `interaction($interaction = null)`   * `param bool` $interaction
* `noInteraction()`  adds `no-interaction` option to composer
* `optimizeAutoloader($optimize = null)`  adds `optimize-autoloader` option to composer
* `ignorePlatformRequirements($ignore = null)`  adds `ignore-platform-reqs` option to composer
* `disablePlugins($disable = null)`  disable plugins
* `noScripts($disable = null)`  skip scripts
* `workingDir($dir)`  adds `--working-dir $dir` option to composer
* `buildCommand()`  Copy class fields into command options as directed.
* `setOutput($output)`  Sets the Console Output.
* `dir($dir)`  Changes working directory of command
* `arg($arg)`  Pass argument to executable. Its value will be automatically escaped.
* `args($args)`  Pass methods parameters as arguments to executable. Argument values
* `rawArg($arg)`  Pass the provided string in its raw (as provided) form as an argument to executable.
* `option($option, $value = null, $separator = null)`  Pass option to executable. Options are prefixed with `--` , value can be provided in second parameter.
* `options(array $options, $separator = null)`  Pass multiple options to executable. The associative array contains
* `optionList($option, $value = null, $separator = null)`  Pass an option with multiple values to executable. Value can be a string or array.

## Validate


Composer Validate

``` php
<?php
// simple execution
$this->taskComposerValidate()->run();
?>
```

* `noCheckAll($noCheckAll = null)`   * `param bool` $noCheckAll
* `noCheckLock($noCheckLock = null)`   * `param bool` $noCheckLock
* `noCheckPublish($noCheckPublish = null)`   * `param bool` $noCheckPublish
* `withDependencies($withDependencies = null)`   * `param bool` $withDependencies
* `strict($strict = null)`   * `param bool` $strict
* `preferDist($preferDist = null)`  adds `prefer-dist` option to composer
* `preferSource()`  adds `prefer-source` option to composer
* `dev($dev = null)`  adds `dev` option to composer
* `noDev()`  adds `no-dev` option to composer
* `ansi($ansi = null)`  adds `ansi` option to composer
* `noAnsi()`  adds `no-ansi` option to composer
* `interaction($interaction = null)`   * `param bool` $interaction
* `noInteraction()`  adds `no-interaction` option to composer
* `optimizeAutoloader($optimize = null)`  adds `optimize-autoloader` option to composer
* `ignorePlatformRequirements($ignore = null)`  adds `ignore-platform-reqs` option to composer
* `disablePlugins($disable = null)`  disable plugins
* `noScripts($disable = null)`  skip scripts
* `workingDir($dir)`  adds `--working-dir $dir` option to composer
* `buildCommand()`  Copy class fields into command options as directed.
* `setOutput($output)`  Sets the Console Output.
* `dir($dir)`  Changes working directory of command
* `arg($arg)`  Pass argument to executable. Its value will be automatically escaped.
* `args($args)`  Pass methods parameters as arguments to executable. Argument values
* `rawArg($arg)`  Pass the provided string in its raw (as provided) form as an argument to executable.
* `option($option, $value = null, $separator = null)`  Pass option to executable. Options are prefixed with `--` , value can be provided in second parameter.
* `options(array $options, $separator = null)`  Pass multiple options to executable. The associative array contains
* `optionList($option, $value = null, $separator = null)`  Pass an option with multiple values to executable. Value can be a string or array.

## RunScript


Composer Run Script

``` php
<?php
// simple execution
$this->taskComposerRunScript()->arg('myscript')->run();

// pass an option to the script
$this->taskComposerRunScript()->arg('myscript')->scriptOption('foo')->run();
?>
```

* `preferDist($preferDist = null)`  adds `prefer-dist` option to composer
* `preferSource()`  adds `prefer-source` option to composer
* `dev($dev = null)`  adds `dev` option to composer
* `noDev()`  adds `no-dev` option to composer
* `ansi($ansi = null)`  adds `ansi` option to composer
* `noAnsi()`  adds `no-ansi` option to composer
* `interaction($interaction = null)`   * `param bool` $interaction
* `noInteraction()`  adds `no-interaction` option to composer
* `optimizeAutoloader($optimize = null)`  adds `optimize-autoloader` option to composer
* `ignorePlatformRequirements($ignore = null)`  adds `ignore-platform-reqs` option to composer
* `disablePlugins($disable = null)`  disable plugins
* `noScripts($disable = null)`  skip scripts
* `workingDir($dir)`  adds `--working-dir $dir` option to composer
* `buildCommand()`  Copy class fields into command options as directed.
* `dir($dir)`  Changes working directory of command
* `arg($arg)`  Pass argument to executable. Its value will be automatically escaped.
* `args($args)`  Pass methods parameters as arguments to executable. Argument values
* `rawArg($arg)`  Pass the provided string in its raw (as provided) form as an argument to executable.
* `option($option, $value = null, $separator = null)`  Pass option to executable. Options are prefixed with `--` , value can be provided in second parameter.
* `options(array $options, $separator = null)`  Pass multiple options to executable. The associative array contains
* `optionList($option, $value = null, $separator = null)`  Pass an option with multiple values to executable. Value can be a string or array.
* `scriptOption($option, $value = null, $separator = null)`  Pass option to the script. Options are prefixed with `--` , value can be provided in second parameter.

## Exec


Composer Exec

``` php
<?php
// simple execution
$this->taskComposerExec()->arg('mycommand')->run();

// pass an option to the command
$this->taskComposerExec()->arg('mycommand')->scriptOption('foo')->run();

// execute a command installed globally
$this->taskComposerExec('composer', true)->arg('mycommand')->run();

?>
```

* `preferDist($preferDist = null)`  adds `prefer-dist` option to composer
* `preferSource()`  adds `prefer-source` option to composer
* `dev($dev = null)`  adds `dev` option to composer
* `noDev()`  adds `no-dev` option to composer
* `ansi($ansi = null)`  adds `ansi` option to composer
* `noAnsi()`  adds `no-ansi` option to composer
* `interaction($interaction = null)`   * `param bool` $interaction
* `noInteraction()`  adds `no-interaction` option to composer
* `optimizeAutoloader($optimize = null)`  adds `optimize-autoloader` option to composer
* `ignorePlatformRequirements($ignore = null)`  adds `ignore-platform-reqs` option to composer
* `disablePlugins($disable = null)`  disable plugins
* `noScripts($disable = null)`  skip scripts
* `workingDir($dir)`  adds `--working-dir $dir` option to composer
* `buildCommand()`  Copy class fields into command options as directed.
* `dir($dir)`  Changes working directory of command
* `arg($arg)`  Pass argument to executable. Its value will be automatically escaped.
* `args($args)`  Pass methods parameters as arguments to executable. Argument values
* `rawArg($arg)`  Pass the provided string in its raw (as provided) form as an argument to executable.
* `option($option, $value = null, $separator = null)`  Pass option to executable. Options are prefixed with `--` , value can be provided in second parameter.
* `options(array $options, $separator = null)`  Pass multiple options to executable. The associative array contains
* `optionList($option, $value = null, $separator = null)`  Pass an option with multiple values to executable. Value can be a string or array.
* `scriptOption($option, $value = null, $separator = null)`  Pass option to the script. Options are prefixed with `--` , value can be provided in second parameter.
