<?php

namespace Automer;

use Automer\Block\Block;
use Automer\Block\Procedure;
use Automer\Block\Test;
use Automer\Exception\FileException;
use Automer\Exception\SyntaxException;

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
        $handle = fopen($this->file, 'r');

        if (!$handle) {
            throw new FileException('There was an error reading the file', $this->file);
        }

        /** @var Block $proc */
        $proc = null;

        $line_no = 0;

        // Loop through lines in file
        while (($line = fgets($handle)) !== false) {
            $line_no++;
            $line = trim($line);

            if (empty($line) || substr($line, 0, 1) === '#') {
                // Line is a comment or empty
                continue;
            }

            if ($proc !== null) {
                if (strtolower(substr($line, 0, 3)) === 'end') {
                    $proc->analyse();

                    if ($proc instanceof Procedure) {
                        $this->container->procedures[] = $proc;
                    } elseif ($proc instanceof Test) {
                        $this->container->tests[] = $proc;
                    }

                    $proc = null;
                    continue;
                }

                $proc->addLine($line);
            } elseif (strtolower(substr($line, 0, 4)) === 'test') {
                $this->container->output->out("Parsing test ($line)", Output::LEVEL_VVV);
                $proc = new Test($this->container);
            } elseif (strtolower(substr($line, 0, 9)) === 'procedure') {
                $this->container->output->out("Parsing procedure ($line)", Output::LEVEL_VVV);
                $proc = new Procedure($this->container);
            } elseif (strtolower(substr($line, 0, 3)) === 'set') {
                throw new SyntaxException('Global vars not yet implemented.', $this->file, $line_no); // TODO: change message
                // $this->container->data->set(Data::SCOPE_GLOBAL, 'x', 'y'); // TODO
            } else {
                throw new SyntaxException("Unexpected token", $this->file, $line_no);
            }
        }
    }
}
