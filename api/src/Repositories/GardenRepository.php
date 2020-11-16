<?php

declare(strict_types = 1);

namespace MyGarden\Repositories;

use MyGarden\Models\Garden;

class GardenRepository extends Repository
{
    public function getUserGarden(int $id): Garden
    {
        $garden = new Garden ($id, 1, 'Wimbledon Garden', 10, 10);

        return $garden;
    }

    public function getUserGardenWithPlants(int $id): Garden
    {
        //SQL userId to get Garden, join from garden's id to garden_id on pivot table (also returning pivot's x and y coordinates)
        //then join to Plants table from plant_id on pivot to id on Plant

        $garden = $this->getUserGarden($id);

        $plants = $this->repositoryCollection->plantRepository->getGardenPlants($garden->getId());

        for ($i = 0; $i < count($plants); $i++){
            $garden->setPlant($plants[$i], $i, $i);
        }

        return $garden;
    }
}