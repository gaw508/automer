<?php

namespace Automer\Command;

use Automer\Exception\SyntaxException;

class Action extends AbstractCommand
{
    protected function getCommandName()
    {
        return 'action';
    }

    public function analyse()
    {
        $this->extractArguments();

        if (count($this->arguments) !== 2) {
            throw new SyntaxException(
                'Expected 2 arguments, got ' . count($this->arguments), $this->file, $this->line_no
            );
        }
    }

    public function run()
    {
        // TODO
    }
}
