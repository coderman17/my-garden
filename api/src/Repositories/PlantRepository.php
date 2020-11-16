<?php

declare(strict_types = 1);

namespace MyGarden\Repositories;

use MyGarden\Models\Plant;

class PlantRepository extends Repository
{
    public function getGardenPlants(int $gardenId): array
    {
        $plants = [
            new Plant (1, 1, 'Nut Tree', 'Latin for Nut'),
            new Plant (1, 1, 'Cherry Tree', 'Latin for Cherry'),
            new Plant (1, 1, 'Holly', 'Latin for Holly')
        ];

        return $plants;
    }
}