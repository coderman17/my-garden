<?php

declare(strict_types=1);

namespace MyGarden\Exceptions;

class OutOfRangeInt extends \Exception
{
    public function __construct(string $parameter, ?int $min = null, ?int $max = null, \Throwable $previous = null)
    {
        $minClause = '';

        $maxClause = '';

        if ($min !== null) {
            $minClause = ' The minimum value is ' . $min . '.';
        }

        if ($max !== null) {
            $maxClause = ' The maximum value is ' . $max . '.';
        }

        $message = 'Parameter \'' . $parameter . '\' is out of range.' . $minClause . $maxClause;

        parent::__construct($message, 400, $previous);
    }
}
