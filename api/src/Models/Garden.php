<?php

declare(strict_types = 1);

namespace MyGarden\Models;

use MyGarden\Exceptions\OutOfRangeInt;
use MyGarden\Exceptions\OverMaxChars;
use MyGarden\Exceptions\UnderMinChars;

class Garden extends Model
{
    protected string $id;

    protected int $userId;

    protected string $name;

    protected int $dimensionX;

    protected int $dimensionY;

    protected array $plantLocations = [];

    /**
     * @param string|null $id
     * @param int $userId
     * @param string $name
     * @param int $dimensionX
     * @param int $dimensionY
     * @throws OutOfRangeInt
     * @throws OverMaxChars
     * @throws UnderMinChars
     */
    public function __construct(?string $id, int $userId, string $name, int $dimensionX, int $dimensionY)
    {
        if ($id !== null) {
            $this->validateParamStringLength('id', $id, Model::UUID_LENGTH, Model::UUID_LENGTH);

            $this->id = $id;
        } else {
            $this->id = uniqid();
        }

        $this->validateParamIntRange('userId', $userId, 0, Model::UNSIGNED_INT_MAX);

        $this->userId = $userId;

        $this->validateParamStringLength('name', $name, 1, 80);

        $this->name = $name;

        $this->validateParamIntRange('dimensionX', $dimensionX, 1, 10);

        $this->dimensionX = $dimensionX;

        $this->validateParamIntRange('dimensionY', $dimensionY, 1, 10);

        $this->dimensionY = $dimensionY;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDimensionX(): int
    {
        return $this->dimensionX;
    }

    public function getDimensionY(): int
    {
        return $this->dimensionY;
    }

    public function setPlantLocation(Plant $plant, int $coordinateX, int $coordinateY): void
    {
        if($plant->getUserId() !== $this->userId){
            //logic exception?
            throw new \InvalidArgumentException('The User associated with the plant does not match the User associated with the garden');
        }

        $this->validateParamIntRange('coordinateX for ' . $plant->getId(), $coordinateX, 1, $this->dimensionX);

        $this->validateParamIntRange('coordinateY for ' . $plant->getId(), $coordinateY, 1, $this->dimensionY);

        if(isset($this->plantLocations[$coordinateX][$coordinateY])){
            //logic exception?
            throw new \InvalidArgumentException('There is already a plant at that location in the garden');
        }

        $this->plantLocations[$coordinateX][$coordinateY] = new PlantLocation($plant->getId(), $coordinateX, $coordinateY);
    }

    public function mapJson(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'dimensionX' => $this->getDimensionX(),
            'dimensionY' => $this->getDimensionY(),
        ];
    }

    /**
     * @return array<int, PlantLocation>
     */
    public function getPlantLocations(): array
    {
        $plantLocations = [];

        foreach ($this->plantLocations as $coordinateY){
            foreach ($coordinateY as $plantLocation){
                array_push($plantLocations, $plantLocation);
            }
        }

        return $plantLocations;
    }
}