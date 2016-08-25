<?php

namespace Automer\Block;

use Automer\Command\CommandInterface;
use Automer\Container;
use Automer\Exception\UnknownCommandException;

abstract class Block
{
    /**
     * @var Container
     */
    private $container;

    private $file;

    private $lines;

    private $commands;

    public function __construct(Container $container, $file)
    {
        $this->container = $container;
        $this->file = $file;
    }

    public function addLine($line, $line_no)
    {
        $this->lines[$line_no] = $line;
    }

    public function analyse()
    {
        foreach ($this->lines as $line_no => $line) {
            $words = explode(' ', $line);
            $command = $words[0];

            $class_name = "\\Automer\\Command\\$command";
            if (!class_exists($class_name)) {
                throw new UnknownCommandException($command, $this->file, $line_no);
            }

            /** @var CommandInterface $command_object */
            $command_object = new $class_name($line, $this->file, $line_no);
            $command_object->analyse();
            $this->commands[$line_no] = $command_object;
        }
    }

    public function run()
    {
        /**
         * @var int $line_no
         * @var CommandInterface $command
         */
        foreach ($this->commands as $line_no => $command) {
            $command->run();
        }
    }
}
