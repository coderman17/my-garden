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

        if ($this->method === 'POST'){
            $this->addPostParams();
        } elseif ($this->method === 'PUT'){
            $this->addPutParams();
        }

        $this->addGetParams();
    }

    protected function addGetParams(): void
    {
        foreach ($_GET as $k => $v){
            $this->params->setItem($k, $v);
        }
    }

    protected function addPostParams(): void
    {
        foreach ($_POST as $k => $v){
            $this->params->setItem($k, $v);
        }
    }

    protected function addPutParams(): void
    {
        $input = file_get_contents("php://input");

        preg_match_all('/(?<=name=\")[^\"]+/', $input, $keys);

        preg_match_all('/(?<=\"\r\n\r\n)[^\r]+/', $input, $values);

        for ($i = 0; $i < count($keys[0]); $i++){
            $this->params->setItem($keys[0][$i], $values[0][$i]);
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