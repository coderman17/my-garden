<?php

declare(strict_types = 1);

namespace MyGarden\Controllers;

class ControllerCollection
{
    public PlantController $plantController;
    public GardenController $gardenController;

    public function __construct(PlantController $plantController, GardenController $gardenController)
    {
        $this->plantController = $plantController;
        $this->gardenController = $gardenController;
    }
}