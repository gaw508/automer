<?php

namespace Automer\Exception;

class RuntimeException extends Exception
{
    public function getFormattedMessage()
    {
        return "Runtime error: {$this->getMessage()}";
    }
}
