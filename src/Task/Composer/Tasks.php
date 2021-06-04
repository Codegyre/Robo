<?php

namespace Robo\Task\Composer;

trait Tasks
{
    /**
     * @param null|string $pathToComposer
     *
     * @return \Robo\Task\Composer\Install|\Robo\Collection\CollectionBuilder
     */
    protected function taskComposerInstall($pathToComposer = null)
    {
        return $this->task(Install::class, $pathToComposer);
    }

    /**
     * @param null|string $pathToComposer
     *
     * @return \Robo\Task\Composer\Update|\Robo\Collection\CollectionBuilder
     */
    protected function taskComposerUpdate($pathToComposer = null)
    {
        return $this->task(Update::class, $pathToComposer);
    }

    /**
     * @param null|string $pathToComposer
     *
     * @return \Robo\Task\Composer\DumpAutoload|\Robo\Collection\CollectionBuilder
     */
    protected function taskComposerDumpAutoload($pathToComposer = null)
    {
        return $this->task(DumpAutoload::class, $pathToComposer);
    }

    /**
     * @param null|string $pathToComposer
     *
     * @return \Robo\Task\Composer\Init|\Robo\Collection\CollectionBuilder
     */
    protected function taskComposerInit($pathToComposer = null)
    {
        return $this->task(Init::class, $pathToComposer);
    }

    /**
     * @param null|string $pathToComposer
     *
     * @return \Robo\Task\Composer\Config|\Robo\Collection\CollectionBuilder
     */
    protected function taskComposerConfig($pathToComposer = null)
    {
        return $this->task(Config::class, $pathToComposer);
    }

    /**
     * @param null|string $pathToComposer
     *
     * @return \Robo\Task\Composer\Validate|\Robo\Collection\CollectionBuilder
     */
    protected function taskComposerValidate($pathToComposer = null)
    {
        return $this->task(Validate::class, $pathToComposer);
    }

    /**
     * @param null|string $pathToComposer
     *
     * @return \Robo\Task\Composer\Remove|\Robo\Collection\CollectionBuilder
     */
    protected function taskComposerRemove($pathToComposer = null)
    {
        return $this->task(Remove::class, $pathToComposer);
    }

    /**
     * @param null|string $pathToComposer
     *
     * @return \Robo\Task\Composer\RequireDependency|\Robo\Collection\CollectionBuilder
     */
    protected function taskComposerRequire($pathToComposer = null)
    {
        return $this->task(RequireDependency::class, $pathToComposer);
    }

    /**
     * @param null|string $pathToComposer
     *
     * @return \Robo\Task\Composer\CreateProject|\Robo\Collection\CollectionBuilder
     */
    protected function taskComposerCreateProject($pathToComposer = null)
    {
        return $this->task(CreateProject::class, $pathToComposer);
    }

    /**
     * @param null|string $pathToComposer
     *
     * @return \Robo\Task\Composer\CreateProject|\Robo\Collection\CollectionBuilder
     */
    protected function taskCheckPlatformReqs($pathToComposer = null)
    {
        return $this->task(CheckPlatformReqs::class, $pathToComposer);
    }

    /**
     * @param bool $global Run "composer global exec"
     * @param null|string $pathToComposer Path to Composer executable
     *
     * @return \Robo\Task\Composer\Exec|\Robo\Collection\CollectionBuilder
     */
    protected function taskComposerExec($pathToComposer = null, $global = false)
    {
        return $this->task(Exec::class, $pathToComposer, $global);
    }

    /**
     * Create a Composer script command.
     *
     * @param null|string $pathToComposer Path to Composer executable
     *
     * @return \Robo\Task\Composer\RunScript|\Robo\Collection\CollectionBuilder
     */
    protected function taskComposerRunScript($pathToComposer = null)
    {
        return $this->task(RunScript::class, $pathToComposer);
    }
}
