<?php

declare(strict_types = 1);

namespace MyGarden\Models;

use MyGarden\Exceptions\ConstructionFailure;
use MyGarden\Exceptions\NotFound;
use MyGarden\Exceptions\OutOfRangeInt;
use MyGarden\Exceptions\OverMaxChars;
use MyGarden\Exceptions\UnderMinChars;
use MyGarden\Repositories\Repository;
use MyGarden\TypedArrays\IntToGardenArray;
use MyGarden\TypedArrays\StringToPlantArray;

class User extends Model
{
    protected string $id;

    protected string $username;

    protected string $email;

    protected string $password;

    protected Repository $repository;

    /**
     * @param string|null $id
     * @param string $username
     * @param string $email
     * @param string $password
     * @throws OverMaxChars
     * @throws UnderMinChars
     * @throws \Exception
     */
    public function __construct(?string $id, string $username, string $email, string $password)
    {
        if ($id !== null) {
            $this->validateParamStringLength('id', $id, Model::UUID_LENGTH, Model::UUID_LENGTH);

            $this->id = $id;
        } else {
            $this->id = uniqid('MG');
        }

        $this->validateParamStringLength('username', $username, 1, 30);

        $this->username = $username;

        //TODO validate email format
        $this->validateParamStringLength('email', $email, 6, 80);

        $this->email = $email;

        $this->password = $password;

        $this->repository = new Repository();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function mapJson(): array
    {
        return [
            'username' => $this->getUsername()
        ];
    }

    /**
     * @return IntToGardenArray
     * @throws \Exception
     * @throws ConstructionFailure
     * @throws \InvalidArgumentException
     */
    public function getGardens(): IntToGardenArray
    {
        $intToGardenArray = new IntToGardenArray();

        $stmt = $this->repository->prepare(
            'SELECT '
                . $this->repository->constructQueryFromAliases(Garden::COLUMN_ALIASES) . ', '
                . $this->repository->constructQueryFromAliases(PlantLocation::COLUMN_ALIASES) . ', '
                . $this->repository->constructQueryFromAliases(Plant::COLUMN_ALIASES) .
            ' FROM
                `gardens`
            LEFT JOIN
                gardens_plants
                ON gardens.id = gardens_plants.garden_id
            LEFT JOIN
                plants
                ON gardens_plants.plant_id = plants.id
            WHERE
                gardens.user_id = :user_id
            ORDER BY
                gardens.id, gardens_plants.coordinate_x, gardens_plants.coordinate_y;'
        );

        $this->repository->execute(
            [
                'user_id' => $this->id
            ],
            $stmt,
            function (){ return false; }
        );

        $previousGardenId = null;

        $garden = null;

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            if ($row[Garden::COLUMN_ALIASES['gardens.id']] !== $previousGardenId) {
                if ($garden !== null){
                    $intToGardenArray->pushItem($garden);
                }

                $garden = $this->repository->gardenFromRow($row);

                $previousGardenId = $garden->getId();
            }

            if ($row[PlantLocation::COLUMN_ALIASES['gardens_plants.plant_id']] !== null){
                if ($garden === null){
                    throw new \Exception('Found pivot table entry without garden table entry');
                }

                $garden->setPlantLocation(
                    $this->repository->plantFromRow($row),
                    $this->repository->plantLocationFromRow($row)
                );
            }
        }

        if ($garden !== null){
            $intToGardenArray->pushItem($garden);
        }

        return $intToGardenArray;
    }

    /**
     * @param string $gardenId
     * @return Garden
     * @throws NotFound
     * @throws \Exception
     * @throws ConstructionFailure
     */
    public function getGarden(string $gardenId): Garden
    {
        $stmt = $this->repository->prepare(
            'SELECT '
                . $this->repository->constructQueryFromAliases(Garden::COLUMN_ALIASES) . ', '
                . $this->repository->constructQueryFromAliases(PlantLocation::COLUMN_ALIASES) . ', '
                . $this->repository->constructQueryFromAliases(Plant::COLUMN_ALIASES) .
            ' FROM
                `gardens`
            LEFT JOIN
                gardens_plants
                ON gardens.id = gardens_plants.garden_id
            LEFT JOIN
                plants
                ON gardens_plants.plant_id = plants.id
            WHERE
                gardens.user_id = :user_id
                AND gardens.id = :id;'
        );

        $this->repository->execute(
            [
                'user_id' => $this->id,
                'id' => $gardenId
            ],
            $stmt,
            function (){ return false; }
        );

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if(!$row){
            throw new NotFound($gardenId);
        }

        $garden = $this->repository->gardenFromRow($row);

        while($row) {
            if ($row[PlantLocation::COLUMN_ALIASES['gardens_plants.plant_id']] !== null){
                    $garden->setPlantLocation(
                        $this->repository->plantFromRow($row),
                        $this->repository->plantLocationFromRow($row)
                    );
            }

            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        return $garden;
    }

    /**
     * @param string $gardenId
     * @throws NotFound
     * @throws \Exception
     */
    public function deleteGarden(string $gardenId): void
    {
        $stmt1 = $this->repository->prepare(
            'DELETE
            FROM `gardens`
            WHERE `user_id` = :user_id
            AND `id` = :id;'
        );

        $stmt2 = $this->repository->prepare(
            'DELETE
            FROM `gardens_plants`
            WHERE `garden_id` = :id;'
        );

        $this->repository->execute(
            [
                'user_id' => $this->id,
                'id' => $gardenId
            ],
            $stmt1,
            function (int $rowCount){ return $rowCount > 1; }
        );

        if($stmt1->rowCount() < 1){
            throw new NotFound($gardenId);
        }

        $this->repository->execute(
            [
                'id' => $gardenId
            ],
            $stmt2,
            function (){ return false; }
        );
    }

    /**
     * @param Garden $garden
     * @throws \Exception
     */
    public function saveGarden(Garden $garden): void
    {
        $stmtGardens = $this->repository->prepare(
            'INSERT INTO `gardens`
            (`id`, `user_id`, `name`, `dimension_x`, `dimension_y`)
            VALUES (:id, :user_id, :name, :dimension_x, :dimension_y);'
        );

        $stmtGardenPlants = $this->repository->prepare(
            'INSERT INTO `gardens_plants`
            (`garden_id`, `plant_id`, `coordinate_x`, `coordinate_y`)
            VALUES (:garden_id, :plant_id, :coordinate_x, :coordinate_y);'
        );

        $plantLocations = $garden->getPlantLocations();

        $this->repository->execute(
            [
                'id' => $garden->getId(),
                'user_id' => $garden->getUserId(),
                'name' => $garden->getName(),
                'dimension_x' => $garden->getDimensionX(),
                'dimension_y' => $garden->getDimensionY(),
            ],
            $stmtGardens,
            function (int $rowCount){ return $rowCount !== 1; }
        );

        foreach($plantLocations as $plantLocation) {
            $this->repository->execute(
                [
                    'garden_id' => $garden->getId(),
                    'plant_id' => $plantLocation->getPlantId(),
                    'coordinate_x' => $plantLocation->getCoordinateX(),
                    'coordinate_y' => $plantLocation->getCoordinateY(),
                ],
                $stmtGardenPlants,
                function (int $rowCount){ return $rowCount !== 1; }
            );
        }
    }

    /**
     * @param Garden $garden
     * @throws \Exception
     * @throws NotFound
     */
    public function updateGarden(Garden $garden): void
    {
        $stmt = $this->repository->prepare(
            'UPDATE `gardens`
            SET `name` = :name,
                `dimension_x` = :dimension_x,
                `dimension_y` = :dimension_y
            WHERE `id` = :id
            AND `user_id` = :user_id;'
        );

        $this->repository->execute(
            [
                'id' => $garden->getId(),
                'user_id' => $this->id,
                'name' => $garden->getName(),
                'dimension_x' => $garden->getDimensionX(),
                'dimension_y' => $garden->getDimensionY(),
            ],
            $stmt,
            function (int $rowCount){ return $rowCount > 1; }
        );

        if(
            $stmt->rowCount() < 1
        ){
            //throws NotFound
            $this->getGarden($garden->getId());
        }

        $stmt = $this->repository->prepare(
            'DELETE FROM `gardens_plants`
            WHERE `garden_id` = :garden_id;'
        );

        $this->repository->execute(
            [
                'garden_id' => $garden->getId(),
            ],
            $stmt,
            function (){ return false; }
        );

        $stmtGardenPlants = $this->repository->prepare(
            'INSERT INTO `gardens_plants`
            (`garden_id`, `plant_id`, `coordinate_x`, `coordinate_y`)
            VALUES (:garden_id, :plant_id, :coordinate_x, :coordinate_y);'
        );

        $plantLocations = $garden->getPlantLocations();

        foreach($plantLocations as $plantLocation) {
            $this->repository->execute(
                [
                    'garden_id' => $garden->getId(),
                    'plant_id' => $plantLocation->getPlantId(),
                    'coordinate_x' => $plantLocation->getCoordinateX(),
                    'coordinate_y' => $plantLocation->getCoordinateY(),
                ],
                $stmtGardenPlants,
                function (int $rowCount){ return $rowCount !== 1; }
            );
        }
    }

    /**
     * @return StringToPlantArray
     * @throws \Exception
     * @throws \InvalidArgumentException
     * @throws ConstructionFailure
     */
    public function getPlants(): StringToPlantArray
    {
        $stringToPlantArray = new StringToPlantArray();

        $stmt = $this->repository->prepare(
            'SELECT ' . $this->repository->constructQueryFromAliases(Plant::COLUMN_ALIASES) .
            ' FROM `plants`
            WHERE `user_id` = :user_id;'
        );

        $this->repository->execute(
            [
                'user_id' => $this->id
            ],
            $stmt,
            function (){ return false; }
        );

        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $plant = $this->repository->plantFromRow($row);

            $stringToPlantArray->setItem($plant->getId(), $plant);
        }

        return $stringToPlantArray;
    }

    /**
     * @param string $plantId
     * @return Plant
     * @throws NotFound
     * @throws \Exception
     * @throws ConstructionFailure
     */
    public function getPlant(string $plantId): Plant
    {
        $stmt = $this->repository->prepare(
            'SELECT '
            . $this->repository->constructQueryFromAliases(Plant::COLUMN_ALIASES) .
            ' FROM `plants`
            WHERE `user_id` = :user_id
            AND `id` = :id;'
        );

        $this->repository->execute(
            [
                'user_id' => $this->id,
                'id' => $plantId
            ],
            $stmt,
            function (int $rowCount){ return $rowCount > 1; }
        );

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if(!$row){
            throw new NotFound($plantId);
        }

        return $this->repository->plantFromRow($row);
    }

    /**
     * @param Garden $garden
     * @param array<PlantLocation> $plantLocations
     * @throws ConstructionFailure
     * @throws NotFound
     * @throws OutOfRangeInt
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function setPlantLocations(Garden $garden, array $plantLocations): void
    {
        $plants = $this->getPlants()->getItems();

        foreach ($plantLocations as $plantLocation){

            $plantId = $plantLocation->getPlantId();

            if(!isset($plants[$plantId])){
                throw new NotFound($plantId);
            }

            $garden->setPlantLocation(
                $plants[$plantId],
                $plantLocation
            );
        }
    }

    /**
     * @param string $plantId
     * @throws NotFound
     * @throws \Exception
     */
    public function deletePlant(string $plantId): void
    {
        $stmt = $this->repository->prepare(
            'DELETE
            FROM `plants`
            WHERE `user_id` = :user_id
            AND `id` = :id;'
        );

        $this->repository->execute(
            [
                'user_id' => $this->id,
                'id' => $plantId
            ],
            $stmt,
            function (int $rowCount){ return $rowCount > 1; }
        );

        if($stmt->rowCount() < 1){
            throw new NotFound($plantId);
        }
    }

    /**
     * @param Plant $plant
     * @throws \Exception
     */
    public function savePlant(Plant $plant): void
    {
        $stmt = $this->repository->prepare(
            'INSERT INTO `plants`
            (`id`, `user_id`, `english_name`, `latin_name`, `image_link`)
            VALUES (:id, :user_id, :english_name, :latin_name, :image_link);'
        );

        $this->repository->execute(
            [
                'id' => $plant->getId(),
                'user_id' => $this->id,
                'english_name' => $plant->getEnglishName(),
                'latin_name' => $plant->getLatinName(),
                'image_link' => $plant->getImageLink(),
            ],
            $stmt,
            function (int $rowCount){ return $rowCount !== 1; }
        );
    }

    /**
     * @param Plant $plant
     * @throws \Exception
     * @throws NotFound
     */
    public function updatePlant(Plant $plant): void
    {
        $stmt = $this->repository->prepare(
            'UPDATE `plants`
            SET `english_name` = :english_name,
            `latin_name` = :latin_name,
            `image_link` = :image_link
            WHERE `id` = :id
            AND `user_id` = :user_id;'
        );

        $this->repository->execute(
            [
                'id' => $plant->getId(),
                'user_id' => $this->id,
                'english_name' => $plant->getEnglishName(),
                'latin_name' => $plant->getLatinName(),
                'image_link' => $plant->getImageLink(),
            ],
            $stmt,
            function (int $rowCount){ return $rowCount > 1; }
        );

        if(
            $stmt->rowCount() < 1
        ){
            //throws Not Found
            $this->getPlant($plant->getId());
        }
    }
}