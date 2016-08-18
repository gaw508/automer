<?php

namespace gaw508\Automer\Command;

use gaw508\Automer\Exception\RuntimeException;
use gaw508\Automer\Exception\SyntaxException;

/**
 * Interface CommandInterface
 *
 * Common interface of a Command
 *
 * @package gaw508\Automer\Command
 * @author George Webb <george@webb.uno>
 */
interface CommandInterface
{
    /**
     * CommandInterface constructor.
     *
     * Construct the command object with the command string
     *
     * @param string $string    The command string (without command key word)
     */
    public function __construct($string);

    /**
     * Perform lexical analysis and parsing of command string
     *
     * @throws SyntaxException  When there is an error with the syntax
     */
    public function analyse();

    /**
     * Run the command
     *
     * @throws RuntimeException
     */
    public function run();
}
