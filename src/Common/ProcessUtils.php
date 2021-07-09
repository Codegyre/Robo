<?php

/*
 * This file is derived from part of the Symfony package, which is
 * (c) Fabien Potencier <fabien@symfony.com>
 */

namespace Robo\Common;

use Symfony\Component\Process\Exception\InvalidArgumentException;

/**
 * ProcessUtils is a bunch of utility methods.
 * These methods are usually private, and are needed to execute and escape
 * some functions for display purposes
 */
class ProcessUtils
{
    /**
     * This class should not be instantiated.
     */
    private function __construct()
    {
    }

    /**
     * Symfony Process has a private method to replace Placeholders in command lines,
     * which we use to rebuild a Command Description.
     *
     * @param string $commandline
     * @param array $env
     * @return string
     */
    public static function replacePlaceholders(string $commandline, array $env)
    {
        return preg_replace_callback('/"\$\{:([_a-zA-Z]++[_a-zA-Z0-9]*+)\}"/', function ($matches) use ($commandline, $env) {
            if (!isset($env[$matches[1]]) || false === $env[$matches[1]]) {
                throw new InvalidArgumentException(sprintf('Command line is missing a value for parameter "%s": ', $matches[1]).$commandline);
            }

            return self::escapeArgument($env[$matches[1]]);
        }, $commandline);
    }

    /**
     * Used by Symfony Process to form the final command line, with escaped parameters.
     * Robo uses placeholders to handle the process arguments without escaping the whole string.
     *
     * @param string|null $argument
     * @return string
     */
    public static function escapeArgument(?string $argument): string
    {
        if ('' === $argument || null === $argument) {
            return '""';
        }
        if ('\\' !== \DIRECTORY_SEPARATOR) {
            return "'".str_replace("'", "'\\''", $argument)."'";
        }
        if (false !== strpos($argument, "\0")) {
            $argument = str_replace("\0", '?', $argument);
        }
        if (!preg_match('/[\/()%!^"<>&|\s]/', $argument)) {
            return $argument;
        }
        $argument = preg_replace('/(\\\\+)$/', '$1$1', $argument);

        return '"'.str_replace(['"', '^', '%', '!', "\n"], ['""', '"^^"', '"^%"', '"^!"', '!LF!'], $argument).'"';
    }
}
