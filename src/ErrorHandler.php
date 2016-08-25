<?php

namespace Automer;

use Exception;

/**
 * Class ErrorHandler
 *
 * @package Automer
 * @author George Webb <george@webb.uno>
 */
class ErrorHandler
{
    /**
     * The DI container
     *
     * @var Container
     */
    private $container;

    /**
     * Holds 20kb of memory for use for handling fatal errors, specifically out of memory errors
     *
     * @var string
     */
    private $reserved_memory;

    /**
     * A list of error types which can be treated as fatal
     *
     * @var array
     */
    private static $fatal_errors = array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR);

    /**
     * A map of php error codes to output level
     *
     * @var array
     */
    private static $map_error_to_output_level = array(
        E_ERROR             => Output::LEVEL_STANDARD,
        E_WARNING           => Output::LEVEL_V,
        E_PARSE             => Output::LEVEL_STANDARD,
        E_NOTICE            => Output::LEVEL_VV,
        E_CORE_ERROR        => Output::LEVEL_STANDARD,
        E_CORE_WARNING      => Output::LEVEL_V,
        E_COMPILE_ERROR     => Output::LEVEL_STANDARD,
        E_COMPILE_WARNING   => Output::LEVEL_V,
        E_USER_ERROR        => Output::LEVEL_STANDARD,
        E_USER_WARNING      => Output::LEVEL_V,
        E_USER_NOTICE       => Output::LEVEL_VV,
        E_STRICT            => Output::LEVEL_VVV,
        E_RECOVERABLE_ERROR => Output::LEVEL_STANDARD,
        E_DEPRECATED        => Output::LEVEL_VVV,
        E_USER_DEPRECATED   => Output::LEVEL_VVV
    );

    /**
     * Maps php error codes to corresponding strings
     *
     * @var array
     */
    private static $map_error_to_string = array(
        E_ERROR             => 'E_ERROR',
        E_WARNING           => 'E_WARNING',
        E_PARSE             => 'E_PARSE',
        E_NOTICE            => 'E_NOTICE',
        E_CORE_ERROR        => 'E_CORE_ERROR',
        E_CORE_WARNING      => 'E_CORE_WARNING',
        E_COMPILE_ERROR     => 'E_COMPILE_ERROR',
        E_COMPILE_WARNING   => 'E_COMPILE_WARNING',
        E_USER_ERROR        => 'E_USER_ERROR',
        E_USER_WARNING      => 'E_USER_WARNING',
        E_USER_NOTICE       => 'E_USER_NOTICE',
        E_STRICT            => 'E_STRICT',
        E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
        E_DEPRECATED        => 'E_DEPRECATED',
        E_USER_DEPRECATED   => 'E_USER_DEPRECATED'
    );

    /**
     * ErrorHandler constructor.
     *
     * @param Container $container  The DI Container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Registers error handler, fatal error handler and exception handler
     *
     * @codeCoverageIgnore
     */
    public function register()
    {
        error_reporting(0);
        $this->registerErrorHandler();
        $this->registerExceptionHandler();
        $this->registerFatalHandler();
    }

    /**
     * Register error handler
     *
     * @codeCoverageIgnore
     */
    private function registerErrorHandler()
    {
        set_error_handler(array($this, 'handleError'), -1);
    }

    /**
     * Register exception handler
     *
     * @codeCoverageIgnore
     */
    private function registerExceptionHandler()
    {
        set_exception_handler(array($this, 'handleException'));
    }

    /**
     * Register fatal error handler
     *
     * @codeCoverageIgnore
     */
    private function registerFatalHandler()
    {
        register_shutdown_function(array($this, 'handleFatalError'));
        $this->reserved_memory = str_repeat(' ', 1024 * 20);
    }

    /**
     * Handle php errors
     *
     * @param int $code         The error code
     * @param string $message   The error message
     * @param string $file      The file the error occured in
     * @param int $line         The line of the error
     * @param array $context    Any variables relevant to the error
     */
    public function handleError($code, $message, $file = '', $line = 0, $context = array())
    {
        // Check if not a fatal error, these get handled by the shutdown func
        if (in_array($code, static::$fatal_errors, true)) {
            return;
        }

        // Output the error
        $level = isset(static::$map_error_to_output_level[$code]) ?
            static::$map_error_to_output_level[$code] : Output::LEVEL_STANDARD;

        $message = static::$map_error_to_string[$code] . ": $message. Code: $code; File: $file; Line: $line.";

        $this->container->output->error($message, $level);
    }

    /**
     * Handle uncaught exceptions
     *
     * @param Exception $e  The uncaught exception
     */
    public function handleException(Exception $e)
    {
        $message = sprintf(
            'Uncaught Exception %s: "%s" at %s line %s',
            get_class($e),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        );

        $this->container->output->error("Unexpected PHP error: $message", Output::LEVEL_VVV);
        $this->container->output->error("There was an unexpected error");

        exit();
    }

    /**
     * Handles a fatal error
     *
     * @codeCoverageIgnore
     */
    public function handleFatalError()
    {
        // Free up our reserved memory
        $this->reserved_memory = null;

        // Check last error is fatal, as this function is called on every script running
        $last_error = error_get_last();
        if (is_array($last_error) && in_array($last_error['type'], static::$fatal_errors, true)) {
            $message = 'Fatal Error (' . static::$map_error_to_string[$last_error['type']]
                . "): {$last_error['message']}. Code: {$last_error['type']}; "
                . "File: {$last_error['file']}; Line: {$last_error['line']}.";

            $this->container->output->error("Unexpected PHP error: $message", Output::LEVEL_VVV);
            $this->container->output->error("There was an unexpected error");

            exit();
        }
    }
}
