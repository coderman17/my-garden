<?php

declare(strict_types = 1);

namespace MyGarden\Exceptions;

class OutOfRangeInt extends \Exception
{
    public string $publicMessage;

    public function __construct(string $parameter, int $min, int $max, \Exception $previous = null)
    {
        $this->publicMessage = 'Parameter \'' . $parameter . '\' should be between ' . strval($min) . ' and ' . strval($max);

        $code = 400;

        parent::__construct($this->publicMessage, $code, $previous);
    }
}