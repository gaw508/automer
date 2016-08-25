<?php

namespace Automer;

use Automer\Exception\Exception;

class Runner
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->container->output = new Output($container, Output::LEVEL_VVV); // TODO: Set level based on config
    }

    public function run()
    {
        $this->outputVersion();

        try {
            $this->analyseFiles();
            foreach ($this->container->tests as $test) {
                // TODO: Catch successful, failed, skipped, incomplete tests
                $test->run();
            }
            return;
        } catch (Exception $e) {
            $this->container->output->error("{$e->getFormattedMessage()}");
        } catch (\Exception $e) {
            $this->container->output->error("Unexpected error: {$e->getMessage()}", Output::LEVEL_VVV);
            $this->container->output->error("There was an unexpected error");
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
}
