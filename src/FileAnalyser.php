<?php

namespace gaw508\Automer;

class FileAnalyser
{
    /**
     * @var Container
     */
    private $container;

    private $file;

    public function __construct(Container $container, $file)
    {
        $this->container = $container;
        $this->file = $file;
    }

    public function analyse()
    {
        // TODO
        // TODO: loop through lines of file, detect tests and procedures
    }
}
