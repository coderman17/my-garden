<?php

declare(strict_types = 1);

namespace MyGarden\Exceptions;

class UnderMinChars extends \Exception
{
    public function __construct(string $parameter, int $minChars, \Throwable $previous = null)
    {
        $message = 'Parameter \'' . $parameter . '\' should have no fewer than ' . $minChars . ' character(s)';

        parent::__construct($message, 400, $previous);
    }
}