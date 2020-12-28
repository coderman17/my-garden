<?php

declare(strict_types = 1);

namespace MyGarden\JsonMappers;

use JsonMapper;

class GardenMapper extends JsonMapper
{
    public function mapJson($garden)
    {
        return [
            'id' => $garden->getId(),
            'name' => $garden->getName(),
            'dimensionX' => $garden->getDimensionX(),
            'dimensionY' => $garden->getDimensionY(),
        ];
    }
}