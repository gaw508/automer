<?php

namespace Automer\Exception;

class UnknownCommandException extends Exception
{
    private $automer_file_path;

    private $automer_line;

    public function __construct($message = '', $file_path = '', $line = 0)
    {
        $this->message = $message;
        $this->automer_file_path = $file_path;
        $this->automer_line = $line;
    }

    public function getAutomerFilePath()
    {
        return $this->automer_file_path;
    }

    public function getAutomerLine()
    {
        return $this->automer_line;
    }
}
