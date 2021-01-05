<?php

declare(strict_types = 1);

namespace MyGarden\Exceptions;

class WrongTypeParameter extends \Exception
{
    public function __construct(string $parameter, string $expectedType, \Exception $previous = null)
    {
        $message = "Parameter '" . $parameter . "' should be of type " . $expectedType;

        parent::__construct($message, 400, $previous);
    }
}