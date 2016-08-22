<?php

namespace gaw508\Automer;

use gaw508\Automer\Exception\FileException;
use gaw508\Automer\Exception\SyntaxException;

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
        // TODO: loop through lines of file, detect tests and procedures
        $handle = fopen($this->file, 'r');

        if (!$handle) {
            throw new FileException(); // TODO: message
        }

        $in_proc_or_test = false;

        // Loop through lines in file
        while (($line = fgets($handle)) !== false) {
            $line = trim($line);

            if (substr($line, 0, 1) === '#') {
                // Line is a comment
                continue;
            }

            if ($in_proc_or_test) {
                echo '.';
                // TODO

                if (strtolower(substr($line, 0, 3)) === 'end') {
                    $in_proc_or_test = false;
                    echo "\n END";
                    // TODO
                }
            } elseif (strtolower(substr($line, 0, 4)) === 'test') {
                echo "TEST: $line \n";
                $in_proc_or_test = true;
                // TODO
            } elseif (strtolower(substr($line, 0, 9)) === 'procedure') {
                echo "PROCEDURE: $line \n";
                $in_proc_or_test = true;
                // TODO
            } elseif (strtolower(substr($line, 0, 3)) === 'set') {
                echo "GLOBAL VAR: $line \n";
                $this->container->data->set(Data::SCOPE_GLOBAL, 'x', 'y'); // TODO
            } else {
                throw new SyntaxException('Expected test, procedure or variable declaration, other found.'); // TODO: change message
            }
        }
    }
}
