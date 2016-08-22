<?php

namespace Automer;

use Automer\Exception\RuntimeException;

class Data
{
    const SCOPE_GLOBAL = 'global';

    const SCOPE_LOCAL = 'local';

    private $globalVariable = array();

    private $localVariables = array();

    public function set($scope, $key, $value)
    {
        $scope .= 'Variables';
        $this->$scope[$key] = $value;
    }

    public function get($key)
    {
        if (isset($this->localVariables[$key])) {
            return $this->localVariables[$key];
        } elseif (isset($this->globalVariable[$key])) {
            return $this->globalVariable[$key];
        }

        throw new RuntimeException(); // TODO add message
    }
}
