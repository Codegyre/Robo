<?php

namespace Robo\Task\Composer;

/**
 * Class Exec
 *
 * @package Robo\Task\Composer
 */
class Exec extends Base
{

    /**
     * @var string
     */
    protected $action = 'exec';

    /**
     * Script options.
     *
     * @var array
     */
    protected $scriptOptions = [];

    /**
     * Add a script option.
     *
     * @param string $option Option name
     * @param string|null $value Option value
     *
     * @return self
     */
    public function scriptOption(string $option, $value = null)
    {
        $this->scriptOptions[$option] = $value;

        return $this;
    }

    /**
     * Add script options to command.
     */
    public function buildCommand()
    {
        parent::buildCommand();

        $this->option('');
        $this->options($this->scriptOptions);
    }

    /**
     * Exec constructor.
     *
     * @param bool $global Run "composer global exec"
     * @param null|string $pathToComposer Path to Composer executable
     *
     * @throws \Robo\Exception\TaskException
     */
    public function __construct($pathToComposer = null, $global = false)
    {
        parent::__construct($pathToComposer);

        if ($global) {
            $this->action = 'global ' . $this->action;
        }
    }

    /**
     * Run the command.
     *
     * @return \Robo\Result
     */
    public function run()
    {
        $command = $this->getCommand();
        $this->printTaskInfo('Executing command: {command}', ['command' => $command]);
        return $this->executeCommand($command);
    }
}
