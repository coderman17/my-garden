<?php

declare(strict_types=1);

namespace MyGarden\Controllers;

use MyGarden\Interfaces\PropertyArrayInterface;
use MyGarden\Models\Plant;

class PlantJsonMapper implements PropertyArrayInterface
{
    /**
     * @var Plant
     */
    private Plant $plant;

    /**
     * @param Plant $plant
     */
    public function __construct(Plant $plant)
    {
        $this->plant = $plant;
    }

    public function getPropertyArray(): array
    {
        return [
            'id' => $this->plant->getId(),
            'englishName' => $this->plant->getEnglishName(),
            'latinName' => $this->plant->getLatinName(),
            'imageLink' => $this->plant->getImageLink(),
        ];
    }
}
