<?php

namespace Automer\Command;

use Automer\Exception\SyntaxException;

class Assert extends AbstractCommand
{
    protected function getCommandName()
    {
        return 'assert';
    }

    public function analyse()
    {
        $this->extractArguments();

        if (count($this->arguments) < 2) {
            throw new SyntaxException(
                'Invalid arguments', $this->file, $this->line_no
            );
        }
    }

    public function run()
    {
        // TODO
    }
}
