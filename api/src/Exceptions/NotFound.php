<?php

declare(strict_types = 1);

namespace MyGarden\Exceptions;

class NotFound extends \Exception
{
    public function __construct(string $resourceId = null, \Exception $previous = null) {
        if ($resourceId == null){
            $this->message = 'The resource was not found, or you are not authorized to access it';
        } else {
            $this->message = 'The resource (id: ' . $resourceId . ') was not found, or you are not authorized to access it';
        }

        parent::__construct($this->message, 404, $previous);
    }
}