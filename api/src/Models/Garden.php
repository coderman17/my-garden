<?php

declare(strict_types=1);

namespace MyGarden\Models;

use MyGarden\Exceptions\OutOfRangeInt;
use MyGarden\Exceptions\OverMaxChars;
use MyGarden\Exceptions\UnderMinChars;
use MyGarden\Interfaces\PropertyArrayInterface;

class Garden extends Model implements PropertyArrayInterface
{
    public const COLUMN_ALIASES = [
        'gardens.id'            =>  'gardensId',
        'gardens.user_id'       =>  'gardensUserId',
        'gardens.name'          =>  'gardensName',
        'gardens.dimension_x'   =>  'gardensDimensionX',
        'gardens.dimension_y'   =>  'gardensDimensionY'
    ];

    protected string $id;

    protected string $userId;

    protected string $name;

    protected int $dimensionX;

    protected int $dimensionY;

    /**
     * @var array<int, array<int, PlantLocation>>
     */
    protected array $plantLocations = [];

    /**
     * @param  string|null $id
     * @param  string      $userId
     * @param  string      $name
     * @param  int         $dimensionX
     * @param  int         $dimensionY
     * @throws OutOfRangeInt
     * @throws OverMaxChars
     * @throws UnderMinChars
     */
    public function __construct(?string $id, string $userId, string $name, int $dimensionX, int $dimensionY)
    {
        if ($id !== null) {
            $this->validateParamStringLength('id', $id, Model::UUID_LENGTH, Model::UUID_LENGTH);

            $this->id = $id;
        } else {
            $this->id = uniqid('MG', true);
        }

        $this->validateParamStringLength('userId', $userId, Model::UUID_LENGTH, Model::UUID_LENGTH);

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

    public function getUserId(): string
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

    /**
     * @param  Plant         $plant
     * @param  PlantLocation $plantLocation
     * @throws OutOfRangeInt
     */
    public function setPlantLocation(Plant $plant, PlantLocation $plantLocation): void
    {
        if ($plant->getUserId() !== $this->userId) {
            //logic exception?
            throw new \InvalidArgumentException(
                'The User associated with the plant does not match the User associated with the garden'
            );
        }

        if ($plant->getId() !== $plantLocation->getPlantId()) {
            //logic exception?
            throw new \InvalidArgumentException('The plant provided does not have the same id as the plant location');
        }

        $coordinateX = $plantLocation->getCoordinateX();

        $coordinateY = $plantLocation->getCoordinateY();

        $this->validateParamIntRange('coordinateX for ' . $plant->getId(), $coordinateX, 1, $this->dimensionX);

        $this->validateParamIntRange('coordinateY for ' . $plant->getId(), $coordinateY, 1, $this->dimensionY);

        if (isset($this->plantLocations[$coordinateX][$coordinateY])) {
            //logic exception?
            throw new \LogicException('There is already a plant at that location in the garden', 400);
        }

        $this->plantLocations[$coordinateX][$coordinateY] = $plantLocation;
    }

    public function getPropertyArray(): array
    {
        $plantLocations = [];

        foreach ($this->getPlantLocations() as $plantLocation) {
            $plantLocations[] = $plantLocation->getPropertyArray();
        }

        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'dimensionX' => $this->getDimensionX(),
            'dimensionY' => $this->getDimensionY(),
            'plantLocations' => $plantLocations
        ];
    }

    /**
     * @return array<int, PlantLocation>
     */
    public function getPlantLocations(): array
    {
        $plantLocations = [];

        foreach ($this->plantLocations as $coordinateY) {
            foreach ($coordinateY as $plantLocation) {
                $plantLocations[] = $plantLocation;
            }
        }

        return $plantLocations;
    }
}
