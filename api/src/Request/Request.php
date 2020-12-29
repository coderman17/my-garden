<?php

declare(strict_types = 1);

namespace MyGarden\Request;

use MyGarden\Exceptions\MissingParameter;
use MyGarden\Exceptions\WrongTypeParameter;

class Request
{
    public string $method;

    /**
     * @var array<string, mixed>
     */
    public array $params = [];

    public string $uri;

    /**
     * @var array<int, string>
     */
    public array $acceptHeader = [];

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];

        if ($this->method === 'POST' || $this->method === 'PUT') {
            $inputJSON = file_get_contents('php://input');

            foreach (json_decode($inputJSON, true) as $k => $v) {
                $this->params[$k] = $v;
            }
        }

        foreach ($_GET as $k => $v){
            $v = $this->identifyInteger($v);

            $this->params[$k] = $v;
        }

        $this->uri = $_SERVER['REQUEST_URI'];

        if ($_SERVER['HTTP_ACCEPT'] !== null) {
            $this->acceptHeader = explode(',', $_SERVER['HTTP_ACCEPT']);
        }
    }

    /**
     * @param string $value
     * @return int|string
     */
    protected function identifyInteger(string $value)
    {
        if (preg_match('/^[1-9][0-9]+$|^[0-9]$/', $value)){
            return intval($value);
        }

        return $value;
    }

    /**
     * @param array<string, mixed> $array
     * @throws MissingParameter
     * @throws WrongTypeParameter
     */
    public function validateExistsWithType(array $array): void
    {
        foreach ($array as $k => $v){
            if(!isset($this->params[$k])){
                throw new MissingParameter($k);
            }

            if(gettype($this->params[$k]) !== $v['type']){
                throw new WrongTypeParameter($k, $v['type']);
            }
        }

    }
}