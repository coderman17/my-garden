<?php

declare(strict_types = 1);

namespace MyGarden\Exceptions;

class UnderMinChars extends \Exception
{
    public string $publicMessage;

    public function __construct($parameter, $minChars, \Exception $previous = null) {
        $this->publicMessage = "Parameter '" . $parameter . "' should have no fewer than " . $minChars . " character(s)";

        $code = 400;

        parent::__construct($this->publicMessage, $code, $previous);
    }
}