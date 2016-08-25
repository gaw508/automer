<?php

namespace Automer;

use League\CLImate\CLImate;

class Container
{
    public $configuration;

    public $tests = array();

    public $procedures = array();

    /**
     * @var Data
     */
    public $data;

    /**
     * @var CLImate
     */
    public $climate;

    /**
     * @var Output
     */
    public $output;

    public $results = array(
        'tests' => 0,
        'assertions' => 0,
        'failures' => 0
    );
}
