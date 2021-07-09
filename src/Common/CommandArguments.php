<?php

namespace Robo\Common;

use Robo\Common\ProcessUtils;

/**
 * Use this to add arguments and options to the $arguments property.
 */
trait CommandArguments
{
    /**
     * @var string
     */
    protected $arguments = '';

    /**
     * @var array
     */
    protected $argumentsEnv = [];


    /**
     * Adds an argument and its placeholder value, to be executed
     *
     * @param string $argument
     * @param string|null $key
     * @param string|null $prefix
     * @param string|null $separator
     * @return void
     */
    protected function addArgument($argument, $key = null, $prefix = null, $separator = ' ')
    {
        if (is_null($key)) {
            $key = "arg" . md5($argument);
        }

        $this->arguments .= null == $prefix ? '' : $separator . $prefix;
        if (!is_null($argument)) {
            $this->arguments .= ' "${:' . $key . '}"';
            $this->argumentsEnv[$key] = $argument;
        }
    }

    /**
     * Escape a command line argument.
     *
     * @param string $argument
     * @return string
     *
     * @deprecated Use \Robo\Common\ProcessUtils::escapeArgument() instead.
     */
    public static function escape($argument)
    {
        if (preg_match('/^[a-zA-Z0-9\/\.@~_-]+$/', $argument)) {
            return $argument;
        }
        return ProcessUtils::escapeArgument($argument);
    }

    /**
     * Pass argument to executable. It will be changed to a placeholder.
     *
     * @param string $arg
     *
     * @return $this
     */
    public function arg($arg)
    {
        return $this->args($arg);
    }

    /**
     * Pass methods parameters as arguments to executable. Argument are
     * automatically passed as placeholders
     *
     * @param string|string[] $args
     *
     * @return $this
     */
    public function args($args)
    {
        $func_args = func_get_args();
        if (!is_array($args)) {
            $args = $func_args;
        }

        foreach ($args as $arg) {
            $this->addArgument($arg);
        }
        return $this;
    }

    /**
     * Pass the provided string in its raw (as provided) form as an argument to executable.
     *
     * @param string $arg
     *
     * @return $this
     */
    public function rawArg($arg)
    {
        $this->arguments .= " $arg";

        return $this;
    }

    /**
     * Pass option to executable. Options are prefixed with `--` , value can be provided in second parameter.
     * Option values are automatically passed as placeholders.
     *
     * @param string $option
     * @param string $value
     * @param string $separator
     *
     * @return $this
     */
    public function option($option, $value = null, $separator = ' ')
    {
        if ($option !== null and strpos($option, '-') !== 0) {
            $option = "--$option";
        }

        $this->addArgument($value, null, $option, $separator);
        return $this;
    }

    /**
     * Pass multiple options to executable. The associative array contains
     * the key:value pairs that become `--key value`, for each item in the array.
     * Values are passed as placeholders.
     *
     * @param array $options
     * @param string $separator
     *
     * @return $this
     */
    public function options(array $options, $separator = ' ')
    {
        foreach ($options as $option => $value) {
            $this->option($option, $value, $separator);
        }
        return $this;
    }

    /**
     * Pass an option with multiple values to executable. Value can be a string or array.
     * Option values are automatically escaped.
     *
     * @param string $option
     * @param string|array $value
     * @param string $separator
     *
     * @return $this
     */
    public function optionList($option, $value = array(), $separator = ' ')
    {
        if (is_array($value)) {
            foreach ($value as $item) {
                $this->optionList($option, $item, $separator);
            }
        } else {
            $this->option($option, $value, $separator);
        }

        return $this;
    }
}
