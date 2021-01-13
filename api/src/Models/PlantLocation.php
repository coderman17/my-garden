<?php

declare(strict_types = 1);

namespace MyGarden\Models;

class PlantLocation extends Model
{
    public const COLUMN_ALIASES = [
        'gardens_plants.garden_id'      =>  'gardensPlantsGardenId',
        'gardens_plants.plant_id'       =>  'gardensPlantsPlantId',
        'gardens_plants.coordinate_x'   =>  'gardensPlantsCoordinateX',
        'gardens_plants.coordinate_y'   =>  'gardensPlantsCoordinateY'
    ];

    protected string $plantId;

    protected int $coordinateX;

    protected int $coordinateY;

    /**
     * @param string $plantId
     * @param int $coordinateX
     * @param int $coordinateY
     */
    public function __construct(string $plantId, int $coordinateX, int $coordinateY)
    {
        $this->plantId = $plantId;

        $this->coordinateX = $coordinateX;

        $this->coordinateY = $coordinateY;
    }

    public function getPlantId(): string
    {
        return $this->plantId;
    }

    public function getCoordinateX(): int
    {
        return $this->coordinateX;
    }

    public function getCoordinateY(): int
    {
        return $this->coordinateY;
    }

    public function mapJson(): array
    {
        return [
            'id' => $this->getPlantId(),
            'coordinateX' => $this->getCoordinateX(),
            'coordinateY' => $this->getCoordinateY()
        ];
    }
}