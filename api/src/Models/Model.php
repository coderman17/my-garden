<?php

declare(strict_types = 1);

namespace MyGarden\Models;

use MyGarden\Exceptions\OutOfRangeInt;
use MyGarden\Exceptions\OverMaxChars;
use MyGarden\Exceptions\UnderMinChars;

abstract class Model
{
    protected const UNSIGNED_INT_MAX = 4294967295;

    protected const UUID_LENGTH = 13;

    //This is here because models should be able to guarantee their own integrity without reliance on the proper use of
    // validation from the controller or elsewhere
    /**
     * @param string $paramName
     * @param string $paramValue
     * @param int|null $minMbChar
     * @param int|null $maxMbChar
     * @throws OverMaxChars
     * @throws UnderMinChars
     */
    public function validateParamStringLength(string $paramName, string $paramValue, ?int $minMbChar = null, ?int $maxMbChar = null): void
    {
        if(
            $minMbChar !== null &&
            mb_strlen($paramValue) < $minMbChar
        ){
            throw new UnderMinChars($paramName, $minMbChar);
        }

        if(
            $maxMbChar !== null &&
            mb_strlen($paramValue) > $maxMbChar
        ){
            throw new OverMaxChars($paramName, $maxMbChar);
        }
    }

    /**
     * @param string $paramName
     * @param int $paramValue
     * @param int|null $min
     * @param int|null $max
     * @throws OutOfRangeInt
     */
    public function validateParamIntRange(string $paramName, int $paramValue, ?int $min = null, ?int $max = null): void
    {
        if(
            $min !== null &&
            $paramValue < $min
        ){
            throw new OutOfRangeInt($paramName, $min, $max);
        }

        if(
            $max !== null &&
            $paramValue > $max
        ){
            throw new OutOfRangeInt($paramName, $min, $max);
        }
    }
}