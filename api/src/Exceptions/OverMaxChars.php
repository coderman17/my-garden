<?php

declare(strict_types = 1);

namespace MyGarden\Exceptions;

class OverMaxChars extends \Exception
{
    public string $publicMessage;

    public function __construct($parameter, $maxChars, \Exception $previous = null) {
        $this->publicMessage = "Parameter '" . $parameter . "' should have no more than " . $maxChars . " character(s)";

        $code = 400;

        parent::__construct($this->publicMessage, $code, $previous);
    }
}