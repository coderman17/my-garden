<?php

declare(strict_types = 1);

namespace MyGarden\Exceptions;

class OutOfRangeInt extends \Exception
{
    public function __construct(string $parameter, int $min, int $max, \Exception $previous = null)
    {
        $message = 'Parameter \'' . $parameter . '\' should be between ' . strval($min) . ' and ' . strval($max);

        parent::__construct($message, 400, $previous);
    }
}