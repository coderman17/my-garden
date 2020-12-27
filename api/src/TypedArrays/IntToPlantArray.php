<?php
/** @noinspection PhpUnused */

declare(strict_types = 1);

namespace MyGarden\TypedArrays;

use MyGarden\Models\Plant;
use TypedArrays\IntToValueArrays\IntToClassArray;

class IntToPlantArray extends IntToClassArray
{
    protected function getClassName(): string
    {
        return Plant::class;
    }
}