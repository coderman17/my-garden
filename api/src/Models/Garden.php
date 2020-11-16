<?php

declare(strict_types = 1);

namespace MyGarden\Models;

class Garden
{
    protected ?int $id;

    protected int $userId;

    protected string $name;

    protected int $width;

    protected int $height;

    protected array $plantLocations = [];

    public function __construct(?int $id, int $userId, string $name, int $width, int $height)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
        $this->width = $width;
        $this->height = $height;
    }

    public function setPlant(Plant $plant, $xCoordinate, $yCoordinate)
    {
        try {
            $this->validatePlantCoordinates($xCoordinate, $yCoordinate);
        } catch (Exception $e){
            echo $e->getMessage();
            return;
        }

        $plantLocation = [
            'plant' => $plant,
            'xCoordinate' => $xCoordinate,
            'yCoordinate' => $yCoordinate
        ];

        array_push($this->plantLocations, $plantLocation);
    }

    /*
     * @throws Exception
     */
    protected function validatePlantCoordinates(int $xCoordinates, int $yCoordinates): void
    {
        if (
            $xCoordinates > $this->width ||
            $yCoordinates > $this->height
        ) {
            throw new Exception('Coordinates are outside of garden width or height');
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDimensions(): array
    {
        return [
            'width' => $this->width,
            'height' => $this->height
        ];
    }

    public function getPlants(): array
    {
        return $this->plantLocations;
    }
}