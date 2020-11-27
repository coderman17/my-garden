<?php

declare(strict_types = 1);

namespace MyGarden\Request;

use TypedArrays\StringToValueArrays\StringToStringArray;

class Request
{
    public string $method;

    public StringToStringArray $params;

    public string $uri;

    public function buildFromSuperglobals(): void
    {
        $this->method = $_SERVER['REQUEST_METHOD'];

        $this->uri = $_SERVER['REQUEST_URI'];

        $this->params = new StringToStringArray();

        if ($this->method === 'POST' || $this->method === 'PUT') {
            $inputJSON = file_get_contents('php://input');

            foreach (json_decode($inputJSON, true) as $k => $v) {
                $this->params->setItem($k, $v);
            };
        }

        foreach ($_GET as $k => $v){
            $this->params->setItem($k, $v);
        }
    }

    public function validateInteger(string $string): int
    {
        if (preg_match('/[^0-9]+/', $string)){
            throw new \Exception('Could not determine an integer in the uri');
        }

        return intval($string);
    }
}