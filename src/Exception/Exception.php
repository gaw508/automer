<?php

namespace Automer\Exception;

class Exception extends \Exception
{
    public function getFormattedMessage()
    {
        return $this->getMessage();
    }
}
