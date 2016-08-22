<?php

namespace Automer\Command;

class Run implements CommandInterface
{
    private $command;

    public function __construct($string)
    {
        $this->command = $string;
    }

    public function analyse()
    {
        // TODO
    }

    public function run()
    {
        // TODO
    }
}
