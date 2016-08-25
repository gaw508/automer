<?php

namespace Automer;

use Automer\Exception\Exception;

class Runner
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function run()
    {
        $this->outputVersion();

        Timer::start('runner');

        try {
            $this->analyseFiles();
            foreach ($this->container->tests as $test) {
                // TODO: Catch successful, failed, skipped, incomplete tests
                $test->run();
            }

            $this->outputMetrics();
            $this->outputResults();

            return;
        } catch (Exception $e) {
            $this->container->output->error("{$e->getFormattedMessage()}");
        }

        exit;
    }

    private function outputVersion()
    {
        $version_string = '0.1.0'; // TODO: get version from git repo or phar instead
        $this->container->output->out("Automer $version_string by George Webb\n");
    }

    private function analyseFiles()
    {
        $files = $this->getFilesToAnalyse();

        foreach ($files as $file) {
            $this->analyseFile($file);
        }
    }

    private function getFilesToAnalyse()
    {
        // TODO: load in files based on configuration
        return array(dirname(__DIR__) . '/example-project/homepage.automer');
    }

    private function analyseFile($file)
    {
        $analyser = new FileAnalyser($this->container, $file);
        $analyser->analyse();
    }

    private function outputMetrics()
    {
        $time_taken = number_format(Timer::stop('runner'), 2);
        $peak_memory = number_format(memory_get_peak_usage(true) / 1000000, 2);
        $this->container->output->out("\nTime taken: $time_taken seconds, Peak Memory: $peak_memory MB");
    }

    private function outputResults()
    {
        if ($this->container->results['failures'] > 0) {
            $this->container->output->outputFail("\nFAIL: {$this->container->results['tests']} tests, "
                . "{$this->container->results['assertions']} assertions, "
                . "{$this->container->results['failures']} failures");
        } else {
            $this->container->output->outputPass("\nPASS: "
            . "{$this->container->results['tests']} tests, {$this->container->results['assertions']} assertions");
        }
    }
}
