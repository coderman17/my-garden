<?php

declare(strict_types = 1);

namespace MyGarden\Exceptions;

class WrongTypeParameter extends \Exception
{
    public string $publicMessage;

    public function __construct($parameter, $expectedType, $code = 0, \Exception $previous = null) {
        $this->publicMessage = "Parameter '" . $parameter . "' should be of type " . $expectedType;

        $code = 400;

        parent::__construct($this->publicMessage, $code, $previous);
    }
}