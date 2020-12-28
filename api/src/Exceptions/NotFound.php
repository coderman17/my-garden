<?php

declare(strict_types = 1);

namespace MyGarden\Exceptions;

class NotFound extends \Exception
{
    public string $publicMessage;

    public function __construct(\Exception $previous = null) {
        $this->publicMessage = 'The requested resource was not found, or you are not authorized to access it';

        $code = 404;

        parent::__construct($this->publicMessage, $code, $previous);
    }
}