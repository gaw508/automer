<?php

namespace Automer;

use Automer\Exception\FileException;
use Automer\Exception\SyntaxException;
use Automer\Exception\UnknownCommandException;

class Runner
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->container->output = new Output($container);
    }

    public function run()
    {
        $this->analyseFiles();

        foreach ($this->container->tests as $test) {
            // TODO: Catch successful, failed, skipped, incomplete tests
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
        try {
            $analyser = new FileAnalyser($this->container, $file);
            $analyser->analyse();
            return;
        } catch (FileException $e) {
            $this->container->output->error("{$e->getMessage()}. ({$e->getAutomerFilePath()})");
        } catch (SyntaxException $e) {
            $this->container->output->error(
                "Syntax error: {$e->getMessage()}. Line {$e->getAutomerLine()} in file {$e->getAutomerFilePath()}"
            );
        } catch (UnknownCommandException $e) {
            $this->container->output->error(
                "{$e->getMessage()}. Line {$e->getAutomerLine()} in file {$e->getAutomerFilePath()}"
            );
        } catch (\Exception $e) {
            $this->container->output->error("Unexpected error: {$e->getMessage()}", Output::LEVEL_VVV);
            $this->container->output->error("There was an unexpected error");
        }
        exit;
    }
}
