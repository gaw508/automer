<?php

namespace Automer\Block;

use Automer\Container;

abstract class Block
{
    /**
     * @var Container
     */
    private $container;

    private $lines;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function addLine($line)
    {
        $this->lines[] = $line;
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
