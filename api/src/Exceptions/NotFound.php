<?php

declare(strict_types = 1);

namespace MyGarden\Exceptions;

class NotFound extends \Exception
{
    public function __construct(string $resourceId = null, \Exception $previous = null)
    {
        $parentheses = '';

        if ($resourceId !== null) {
            $parentheses = '(id: ' . $resourceId . ') ';
        }

        $message = 'The resource ' . $parentheses . 'was not found, or you are not authorized to access it';

        parent::__construct($message, 404, $previous);
    }
}