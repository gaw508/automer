<?php

namespace Automer;

class Output
{
    const LEVEL_STANDARD = 0;

    const LEVEL_V = 1;

    const LEVEL_VV = 2;

    const LEVEL_VVV = 3;

    /**
     * @var Container
     */
    private $container;

    private $level;

    public function __construct($container, $level = Output::LEVEL_STANDARD)
    {
        $this->container = $container;
        $this->level = $level;
    }

    public function out($text, $level = Output::LEVEL_STANDARD)
    {
        if ($level <= $this->level) {
            $this->container->climate->out($text);
        }
    }

    public function inline($text, $level = Output::LEVEL_STANDARD)
    {
        if ($level <= $this->level) {
            $this->container->climate->inline($text);
        }
    }

    public function error($text, $level = Output::LEVEL_STANDARD)
    {
        if ($level <= $this->level) {
            $this->container->climate->error($text);
        }
    }

    public function outputPass($message)
    {
        $this->container->climate->black()->backgroundGreen()->out($message);
    }

    public function outputFail($message)
    {
        $this->container->climate->backgroundRed()->out($message);
    }
}
