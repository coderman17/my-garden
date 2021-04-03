<?php

declare(strict_types=1);

namespace MyGarden\Exceptions;

class OverMaxChars extends \Exception
{
    public function __construct(string $parameter, int $maxChars, \Throwable $previous = null)
    {
        $message = 'Parameter \'' . $parameter . '\' should have no more than ' . $maxChars . ' character(s)';

        parent::__construct($message, 400, $previous);
    }
}
