<?php

namespace Automer\Command;

use Automer\Exception\SyntaxException;

abstract class AbstractCommand implements CommandInterface
{
    const ARG_TYPE_STRING_LITERAL = 'string_literal';

    const ARG_TYPE_VARIABLE = 'variable';

    protected $command;

    protected $file;

    protected $line_no;

    protected $arguments = array();

    public function __construct($command, $file, $line_no)
    {
        $this->command = $command;
        $this->file = $file;
        $this->line_no = $line_no;
    }

    protected abstract function getCommandName();

    protected function getArgumentString()
    {
        return trim(substr($this->command, strlen($this->getCommandName())));
    }

    protected function extractArguments()
    {
        $command = $this->getArgumentString();
        $current_arg = false;
        for ($i = 0; $i < strlen($command); $i++) {
            if (!$current_arg) {
                if ($command[$i] === ' ') {
                    continue;
                } elseif ($command[$i] === '"' || $command[$i] === "'") {
                    $current_arg = array(
                        'type' => static::ARG_TYPE_STRING_LITERAL,
                        'delimiter' => $command[$i],
                        'value' => ''
                    );
                } else {
                    $current_arg = array(
                        'type' => static::ARG_TYPE_VARIABLE,
                        'value' => $command[$i]
                    );
                }
            } elseif ($current_arg['type'] === static::ARG_TYPE_VARIABLE) {
                if ($command[$i] === ' ') {
                    $this->arguments[] = $current_arg;
                    $current_arg = false;
                } else {
                    $current_arg['value'] .= $command[$i];
                }
            } elseif ($current_arg['type'] === static::ARG_TYPE_STRING_LITERAL) {
                if ($command[$i] === $current_arg['delimiter']) {
                    $this->arguments[] = $current_arg;
                    $current_arg = false;
                } else {
                    $current_arg['value'] .= $command[$i];
                }
            }

            if ($i + 1 === strlen($command) && $current_arg) {
                if ($current_arg['type'] === static::ARG_TYPE_STRING_LITERAL) {
                    throw new SyntaxException('Unclosed string literal', $this->file, $this->line_no);
                }

                $this->arguments[] = $current_arg;
                $current_arg = false;
            }
        }
    }
}
