<?php

namespace Robo\Common;

use Psr\Log\LoggerAwareTrait;
use Robo\Result;
use Symfony\Component\Process\Process;

/**
 * Class ExecTrait
 * @package Robo\Common
 */
trait ExecTrait
{
    /**
     * @var bool
     */
    protected $background = false;

    /**
     * @var null|int
     */
    protected $timeout = null;

    /**
     * @var null|int
     */
    protected $idleTimeout = null;

    /**
     * @var null|array
     */
    protected $env = null;

    /**
     * @var Process
     */
    protected $process;

    /**
     * @var resource|string
     */
    protected $input;

    /**
     * @var boolean
     */
    protected $interactive = false;

    /**
     * @var bool
     */
    protected $isPrinted = true;

    /**
     * @var bool
     */
    protected $isMetadataPrinted = true;

    /**
     * @var string
     */
    protected $workingDirectory;

    /** @var string */
    protected $command;

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Sets $this->interactive() based on posix_isatty().
     */
    public function detectInteractive()
    {
        if (!isset($this->interactive) && function_exists('posix_isatty')) {
            $this->interactive = posix_isatty(STDOUT);
        }
    }

    /**
     * Executes command in background mode (asynchronously)
     *
     * @return $this
     */
    public function background($arg = true)
    {
        $this->background = $arg;
        return $this;
    }

    /**
     * Stop command if it runs longer then $timeout in seconds
     *
     * @param int $timeout
     *
     * @return $this
     */
    public function timeout($timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * Stops command if it does not output something for a while
     *
     * @param int $timeout
     *
     * @return $this
     */
    public function idleTimeout($timeout)
    {
        $this->idleTimeout = $timeout;
        return $this;
    }

    /**
     * Sets the environment variables for the command
     *
     * @param array $env
     *
     * @return $this
     */
    public function env(array $env)
    {
        $this->env = $env;
        return $this;
    }

    /**
     * Pass an input to the process. Can be resource created with fopen() or string
     *
     * @param resource|string $input
     *
     * @return $this
     */
    public function setInput($input)
    {
        $this->input = $input;
        return $this;
    }

    /**
     * Attach tty to process for interactive input
     *
     * @param $interactive bool
     *
     * @return $this
     */
    public function interactive($interactive)
    {
        $this->interactive = $interactive;
        return $this;
    }


    /**
     * Is command printing its output to screen
     *
     * @return bool
     */
    public function getPrinted()
    {
        return $this->isPrinted;
    }

    /**
     * Changes working directory of command
     *
     * @param string $dir
     *
     * @return $this
     */
    public function dir($dir)
    {
        $this->workingDirectory = $dir;
        return $this;
    }

    /**
     * Shortcut for setting isPrinted() and isMetadataPrinted() to false.
     *
     * @param bool $arg
     *
     * @return $this
     */
    public function silent($arg)
    {
        if (is_bool($arg)) {
            $this->isPrinted = !$arg;
            $this->isMetadataPrinted = !$arg;
        }
        return $this;
    }

    /**
     * Should command output be printed
     *
     * @param bool $arg
     *
     * @return $this
     *
     * @deprecated
     */
    public function printed($arg)
    {
        $this->logger->warning("printed() is deprecated. Please use printOutput().");
        return $this->printOutput($arg);
    }

    /**
     * Should command output be printed
     *
     * @param bool $arg
     *
     * @return $this
     */
    public function printOutput($arg)
    {
        if (is_bool($arg)) {
            $this->isPrinted = $arg;
        }
        return $this;
    }

    /**
     * Should command metadata be printed. I,e., command and timer.
     *
     * @param bool $arg
     *
     * @return $this
     */
    public function printMetadata($arg)
    {
        if (is_bool($arg)) {
            $this->isMetadataPrinted = $arg;
        }
        return $this;
    }

    /**
     *
     */
    public function __destruct()
    {
        if (!$this->background()) {
            $this->stop();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->execute($this->getCommand());
    }

    /**
     * @param string $command
     * @param callable $output_callback
     *
     * @return \Robo\Result
     */
    protected function execute($command, $output_callback = null)
    {
        if (!$output_callback) {
            $output_callback = function ($type, $buffer) {
                print($buffer);
            };
        }

        if ($this->isMetadataPrinted) {
            $this->printAction();
        }
        $this->process = new Process($command);
        $this->process->setTimeout($this->timeout);
        $this->process->setIdleTimeout($this->idleTimeout);
        $this->process->setWorkingDirectory($this->workingDirectory);

        if ($this->input) {
            $this->process->setInput($this->input);
        }

        if ($this->interactive) {
            $this->process->setTty(true);
        }

        if (isset($this->env)) {
            $this->process->setEnv($this->env);
        }

        if (!$this->background and !$this->isPrinted) {
            $this->startTimer();
            $this->process->run();
            $this->stopTimer();
            return new Result(
                $this,
                $this->process->getExitCode(),
                $this->process->getOutput(),
                $this->getResultData()
            );
        }

        if (!$this->background and $this->isPrinted) {
            $this->startTimer();
            $this->process->run($output_callback);
            $this->stopTimer();
            return new Result(
                $this,
                $this->process->getExitCode(),
                $this->process->getOutput(),
                $this->getResultData()
            );
        }

        try {
            $this->process->start();
        } catch (\Exception $e) {
            return Result::fromException($this, $e);
        }
        return Result::success($this);
    }

    /**
     *
     */
    protected function stop()
    {
        if ($this->background && $this->process->isRunning()) {
            $this->process->stop();
            $this->printTaskInfo(
                "Stopped {command}",
                ['command' => $this->getCommand()]
            );
        }
    }

    /**
     * @param array $context
     */
    protected function printAction($context = [])
    {
        $command = $this->getCommand();
        $dir = $this->workingDirectory ? " in {dir}" : "";
        $this->printTaskInfo("Running {command}$dir", [
                'command' => $command,
                'dir' => $this->workingDirectory
            ] + $context);
    }

    /**
     * Gets the data array to be passed to Result().
     *
     * @return array
     *   The data array passed to Result().
     */
    protected function getResultData()
    {
        if ($this->isMetadataPrinted) {
            return ['time' => $this->getExecutionTime()];
        }

        return [];
    }
}
