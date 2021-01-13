<?php

declare(strict_types = 1);

namespace MyGarden\Exceptions;

class ConstructionFailure extends \Exception
{
    public function __construct(\Throwable $previous = null)
    {
        $message = 'Could not construct a class from database data';

        parent::__construct($message, 500, $previous);
    }
}