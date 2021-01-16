<?php
/** @noinspection PhpUnused */

declare(strict_types = 1);

namespace MyGarden\TypedArrays;

use MyGarden\Models\Garden;
use TypedArrays\IntToClassArray;

class IntToGardenArray extends IntToClassArray
{
    protected function getClassName(): string
    {
        return Garden::class;
    }
}