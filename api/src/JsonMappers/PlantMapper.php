<?php

declare(strict_types = 1);

namespace MyGarden\JsonMappers;

use JsonMapper;

class PlantMapper extends JsonMapper
{
    public function mapJson($plant)
    {
        return [
            'id' => $plant->getId(),
            'englishName' => $plant->getEnglishName(),
            'latinName' => $plant->getLatinName(),
            'imageLink' => $plant->getImageLink(),
        ];
    }
}