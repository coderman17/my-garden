<?php

declare(strict_types = 1);

namespace MyGarden\Request;

use MyGarden\Exceptions\UnderMinChars;
use MyGarden\Exceptions\MissingParameter;
use MyGarden\Exceptions\OverMaxChars;

class Request
{
    public string $method;

    public array $params;

    public string $uri;

    public function buildFromSuperglobals(): void
    {
        $this->method = $_SERVER['REQUEST_METHOD'];

        $this->uri = $_SERVER['REQUEST_URI'];

        if ($this->method === 'POST' || $this->method === 'PUT') {
            $inputJSON = file_get_contents('php://input');

            foreach (json_decode($inputJSON, true) as $k => $v) {
                $this->params[$k] = $v;
            };
        }

        foreach ($_GET as $k => $v){
            $this->params[$k] = $v;
        }
    }

    public function validateInteger(string $string): int
    {
        if (preg_match('/[^0-9]+/', $string)){
            throw new \Exception('Could not determine an integer in the uri');
        }

        return intval($string);
    }

    public function validateExistsWithType(array $array): void
    {
        foreach ($array as $k => $v){
            if(!isset($this->params[$k])){
                throw new MissingParameter($k);
            }

            if(gettype($this->params[$k]) !== $v['type']){
                throw new OverMaxChars($k, $v['type']);
            }

            if(
                $v['type'] === 'string' &&
                isset($v['maxChar']) &&
                strlen($this->params[$k]) > $v['maxChar']
            ){
                throw new OverMaxChars($k, $v['maxChar']);
            }

            if(
                $v['type'] === 'string' &&
                isset($v['minChar']) &&
                strlen($this->params[$k]) < $v['minChar']
            ){
                throw new UnderMinChars($k, $v['minChar']);
            }
        }

    }
}