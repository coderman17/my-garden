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
        $this->recursiveParamChecker($array, $this->params);
    }

    protected function recursiveParamChecker(array $spec, array $params): void
    {
        foreach ($spec as $keyName => $attributes){
            var_dump($attributes);
                if (!isset($params[$keyName])) {
                    if ($attributes['optional'] === true) {
                        continue;
                    }
                    throw new MissingParameter(strval($keyName));
                }

            if(gettype($params[$keyName]) !== $attributes['type']){
                throw new WrongTypeParameter(strval($keyName), $attributes['type']);
            }

            if($attributes['type'] === 'array'){
                if ($attributes['arrayType'] == 'indexed'){
                    //If the array is indexed, the spec only needs to list types for the first element.
                    //This foreach uses the spec for that first element to check every subsequent element in the array
                    foreach($params[$keyName] as $item){
                        $this->recursiveParamChecker($attributes['contents'][0]['contents'], $item);
                    }
                } elseif ($attributes['arrayType'] == 'associative'){
                    //if the array is associative, then nothing is assumed and every element in the array
                    //is expected to be listed in the spec
                    $this->recursiveParamChecker($attributes['contents'], $params[$keyName]);
                }
            }
        }
    }
}