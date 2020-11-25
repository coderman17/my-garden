<?php

declare(strict_types = 1);

namespace MyGarden\Request;

use TypedArrays\StringToValueArrays\StringToStringArray;

class Request
{
    public string $requestMethod;

    public StringToStringArray $body;

    public string $uri;

    public function buildFromSuperglobals(): void
    {
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];

        if ($this->requestMethod === 'POST'){
            $this->fillBodyFromPost();
        } elseif ($this->requestMethod === 'PUT'){
            $this->fillBodyFromPut();
        }

        $this->uri = $_SERVER['REQUEST_URI'];
    }

    protected function fillBodyFromPost(): void
    {
        $this->body = new StringToStringArray();

        foreach ($_POST as $k => $v){
            $this->body->setItem($k, $v);
        }
    }

    protected function fillBodyFromPut(): void
    {
        $this->body = new StringToStringArray();

        $input = file_get_contents("php://input");

        preg_match_all('/(?<=name=\")[^\"]+/', $input, $keys);

        preg_match_all('/(?<=\"\r\n\r\n)[^\r]+/', $input, $values);

        for ($i = 0; $i < count($keys[0]); $i++){
            $this->body->setItem($keys[0][$i], $values[0][$i]);
        }
    }
}