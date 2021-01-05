<?php

declare(strict_types = 1);

namespace MyGarden\Exceptions;

class MissingParameter extends \Exception
{
    public function __construct(string $parameter, \Exception $previous = null)
    {
        $message = "Parameter '" . $parameter . "' is required";

        parent::__construct($message, 400, $previous);
    }
}