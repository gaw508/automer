<?php

namespace Automer\Command;

use Automer\Exception\RuntimeException;
use Automer\Exception\SyntaxException;

/**
 * Interface CommandInterface
 *
 * Common interface of a Command
 *
 * @package Automer\Command
 * @author George Webb <george@webb.uno>
 */
interface CommandInterface
{
    /**
     * CommandInterface constructor.
     *
     * Construct the command object with the command string
     *
     * @param string $command   The command string (without command key word)
     * @param string $file      The path of the file the command is in
     * @param string $line_no   The line number of the file
     */
    public function __construct($command, $file, $line_no);

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
