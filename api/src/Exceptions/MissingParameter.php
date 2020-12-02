<?php

declare(strict_types = 1);

namespace MyGarden\Exceptions;

class MissingParameter extends \Exception
{
    public string $publicMessage;

    public function __construct($parameter, \Exception $previous = null) {
        $this->publicMessage = "Parameter '" . $parameter . "' is required";

        $code = 400;

        parent::__construct($this->publicMessage, $code, $previous);
    }
}