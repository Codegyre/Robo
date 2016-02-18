<?php

/*
 * This file may be submitted to the Symfony package, which is:
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Robo\Common; // maybe: namespace Symfony\Component\Console\Logger;

use Robo\Common\ConsoleLogLevel; // maybe: use Symfony\Component\Console\ConsoleLogLevel;
// use Symfony\Component\Console\Logger\ConsoleLogger;

use Psr\Log\AbstractLogger;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\StringInput;

/**
 * Extend Symfony\Component\Console\Logger\ConsoleLogger
 * so that each of the different log level messages are
 * routed through the corresponding SymfonyStyle formatting
 * method.  Log messages are always sent to stderr if the
 * provided output object implements ConsoleOutputInterface.
 *
 * @author Greg Anderson <greg.1.anderson@greenknowe.org>
 */
class StyledConsoleLogger extends AbstractLogger // extends ConsoleLogger
{
    protected $outputStyler;
    protected $errorStyler;
    protected $stylerClassname;

    protected $formatFunctionMap = array(
        LogLevel::EMERGENCY => 'error',
        LogLevel::ALERT => 'error',
        LogLevel::CRITICAL => 'error',
        LogLevel::ERROR => 'error',
        LogLevel::WARNING => 'warning',
        LogLevel::NOTICE => 'note',
        LogLevel::INFO => 'note',
        LogLevel::DEBUG => 'note',
        ConsoleLogLevel::SUCCESS => 'success',
    );

    /**
     * @param OutputInterface $output
     * @param array           $verbosityLevelMap
     * @param array           $formatLevelMap
     * @param array           $formatFunctionMap
     * @param string          $stylerClassname
     */
    public function __construct(OutputInterface $output, array $verbosityLevelMap = array(), array $formatLevelMap = array(), array $formatFunctionMap = array(), $stylerClassname = null)
    {
        // parent::__construct($output, $verbosityLevelMap, $formatLevelMap);
        $this->formatFunctionMap = $formatFunctionMap + $this->formatFunctionMap;
        $this->stylerClassname = $stylerClassname;

        $this->output = $output;
        $this->verbosityLevelMap = $verbosityLevelMap + $this->verbosityLevelMap;
        $this->formatLevelMap = $formatLevelMap + $this->formatLevelMap;
    }

    protected function createStyler(OutputInterface $output)
    {
        // If no styler classname was given, create a SymfonyStyle
        $classname = $this->stylerClassname;
        if (!$classname) {
            $classname = '\Robo\Common\SymfonyLogStyle';
        }
        $styler = new $classname($output);

        return $styler;
    }

    protected function getOutputStyler()
    {
        if (!isset($this->outputStyler)) {
            $this->outputStyler = $this->createStyler($this->output);
        }
        return $this->outputStyler;
    }

    protected function getErrorStyler()
    {
        if (!$this->output instanceof ConsoleOutputInterface) {
            return $this->getOutputStyler();
        }
        if (!isset($this->errorStyler)) {
            $this->errorStyler = $this->createStyler($this->output->getErrorOutput());
        }
        return $this->errorStyler;
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, $message, array $context = array())
    {
        // We use the '_level' context variable to allow log messages
        // to be logged at one level (e.g. NOTICE) and formatted at another
        // level (e.g. SUCCESS). This helps in instances where we want
        // to style log messages at a custom log level that might not
        // be available in all loggers. If the logger does not recognize
        // the log level, then it is treated like the original log level.
        if (array_key_exists('_level', $context) && array_key_exists($context['_level'], $this->verbosityLevelMap)) {
            $level = $this->formatFunctionMap[$context['_level']];
        }
        // It is a runtime error if someone logs at a log level that
        // we do not recognize.
        if (!isset($this->verbosityLevelMap[$level])) {
            throw new InvalidArgumentException(sprintf('The log level "%s" does not exist.', $level));
        }

        // Write to the error output if necessary and available.
        // Usually, loggers that log to a terminal should send
        // all log messages to stderr.
        if (array_key_exists($level, $this->formatLevelMap) && ($this->formatLevelMap[$level] !== self::ERROR)) {
            $outputStyler = $this->getOutputStyler();
        } else {
            $outputStyler = $this->getErrorStyler();
        }

        // Ignore messages that are not at the right verbosity level
        if ($this->output->getVerbosity() >= $this->verbosityLevelMap[$level]) {
            $formatFunction = 'text';
            if (array_key_exists($level, $this->formatFunctionMap)) {
                $formatFunction = $this->formatFunctionMap[$level];
            }
            $outputStyler->$formatFunction($this->interpolate($message, $outputStyler->style($context)), $context);
        }
    }

    public function success($message, array $context = array())
    {
        $this->log(ConsoleLogLevel::SUCCESS, $message, $context);
    }

    // The functions below could be eliminated if made `protected` intead
    // of `private` in ConsoleLogger

    const INFO = 'info';
    const ERROR = 'error';

    /**
     * @var OutputInterface
     */
    private $output;
    /**
     * @var array
     */
    private $verbosityLevelMap = array(
        LogLevel::EMERGENCY => OutputInterface::VERBOSITY_NORMAL,
        LogLevel::ALERT => OutputInterface::VERBOSITY_NORMAL,
        LogLevel::CRITICAL => OutputInterface::VERBOSITY_NORMAL,
        LogLevel::ERROR => OutputInterface::VERBOSITY_NORMAL,
        LogLevel::WARNING => OutputInterface::VERBOSITY_NORMAL,
        LogLevel::NOTICE => OutputInterface::VERBOSITY_VERBOSE,
        LogLevel::INFO => OutputInterface::VERBOSITY_VERY_VERBOSE,
        LogLevel::DEBUG => OutputInterface::VERBOSITY_DEBUG,
        ConsoleLogLevel::SUCCESS => OutputInterface::VERBOSITY_NORMAL,
    );

    /**
     * @var array
     */
    private $formatLevelMap = array(
        LogLevel::EMERGENCY => self::ERROR,
        LogLevel::ALERT => self::ERROR,
        LogLevel::CRITICAL => self::ERROR,
        LogLevel::ERROR => self::ERROR,
        LogLevel::WARNING => self::ERROR,
        LogLevel::NOTICE => self::ERROR,
        LogLevel::INFO => self::ERROR,
        LogLevel::DEBUG => self::ERROR,
        ConsoleLogLevel::SUCCESS => self::ERROR,
    );

    /**
     * Interpolates context values into the message placeholders.
     *
     * @author PHP Framework Interoperability Group
     *
     * @param string $message
     * @param array  $context
     *
     * @return string
     */
    private function interpolate($message, array $context)
    {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace[sprintf('{%s}', $key)] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }
}
