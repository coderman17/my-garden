<?php
/** @noinspection PhpUnused */

declare(strict_types = 1);

namespace MyGarden\TypedArrays;

use MyGarden\Models\Plant;
use TypedArrays\StringToClassArray;

class StringToPlantArray extends StringToClassArray
{
    protected function getClassName(): string
    {
        return Plant::class;
    }
}