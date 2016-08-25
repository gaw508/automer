<?php

namespace Automer\Exception;

class SyntaxException extends Exception
{
    private $automer_file_path;

    private $automer_line;

    public function __construct($message = '', $file_path = '', $line = 0)
    {
        $this->message = $message;
        $this->automer_file_path = $file_path;
        $this->automer_line = $line;
    }

    public function getFormattedMessage()
    {
        return "Syntax error: {$this->getMessage()}. Line {$this->automer_line} in file {$this->automer_file_path}";
    }
}
