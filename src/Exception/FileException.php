<?php

namespace Automer\Exception;

class FileException extends Exception
{
    private $automer_file_path = '';

    public function __construct($message, $file_path)
    {
        $this->message = $message;
        $this->automer_file_path = $file_path;
    }

    public function getFormattedMessage()
    {
        return "{$this->getMessage()}. ({$this->automer_file_path})";
    }
}
