<?php

namespace gaw508\Automer;

class Runner
{
    private $container;

    public function __construct(Configuration $configuration)
    {
        $this->container = new Container();
        $this->container->configuration = $configuration;
    }

    public function run()
    {
        $this->analyseFiles();

        foreach ($this->container->tests as $test) {
            $test->run();
        }
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
