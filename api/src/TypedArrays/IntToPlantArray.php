<?php

declare(strict_types = 1);

namespace MyGarden\TypedArrays;

use TypedArrays\IntToValueArrays\IntToClassArray;

class IntToPlantArray extends IntToClassArray
{
    public string $className = 'MyGarden\Models\Plant';
}