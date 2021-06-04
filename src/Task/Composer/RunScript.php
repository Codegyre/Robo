<?php

namespace Robo\Task\Composer;

/**
 * Class RunScript
 *
 * @package Robo\Task\Composer
 */
class RunScript extends Base
{

    /**
     * @var string
     */
    protected $action = 'run-script';


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
     * Run the command.
     *
     * @return \Robo\Result
     */
    public function run()
    {
        $command = $this->getCommand();
        $this->printTaskInfo('Running script: {command}', ['command' => $command]);
        return $this->executeCommand($command);
    }
}
