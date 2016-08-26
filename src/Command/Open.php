<?php

namespace Automer\Command;

use Automer\Exception\SyntaxException;

class Open extends AbstractCommand
{
    protected function getCommandName()
    {
        return 'open';
    }

    public function analyse()
    {
        $this->extractArguments();

        if (count($this->arguments) !== 1) {
            throw new SyntaxException(
                'Expected 1 argument, got ' . count($this->arguments), $this->file, $this->line_no
            );
        }
    }

    public function run()
    {
        // TODO
    }
}
