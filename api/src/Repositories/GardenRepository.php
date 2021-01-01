<?php

declare(strict_types = 1);

namespace MyGarden\Repositories;

use MyGarden\Exceptions\NotFound;
use MyGarden\Exceptions\OutOfRangeInt;
use MyGarden\Exceptions\OverMaxChars;
use MyGarden\Exceptions\UnderMinChars;
use MyGarden\Models\Garden;
use MyGarden\Models\Plant;
use MyGarden\TypedArrays\IntToGardenArray;

class GardenRepository extends Repository
{

    /**
     * @param int $userId
     * @return IntToGardenArray
     * @throws OutOfRangeInt
     * @throws OverMaxChars
     * @throws UnderMinChars
     * @throws \Exception
     */
    public function getUserGardens(int $userId): IntToGardenArray
    {
        $intToGardenArray = new IntToGardenArray();

        $stmt = $this->repositoryCollection->databaseConnection->dbh->prepare(
            'SELECT *
            FROM `gardens`
            WHERE `user_id` = :user_id;'
        );

        if (!$stmt instanceOf \PDOStatement){
            throw new \Exception('Could not prepare database statement');
        }

        $stmt->execute([
           'user_id' => $userId
       ]);

        while($row = $stmt->fetch(\PDO::FETCH_OBJ)) {
            $garden = new Garden(
                $row->id,
                $row->user_id,
                $row->name,
                $row->dimension_x,
                $row->dimension_y
            );
            $intToGardenArray->pushItem($garden);
        }

        return $intToGardenArray;
    }

    /**
     * @param Garden $garden
     * @throws \Exception
     */
    public function saveUserGarden(Garden $garden): void
    {
        $stmtGardens = $this->prepare(
            'INSERT INTO `gardens`
            (`id`, `user_id`, `name`, `dimension_x`, `dimension_y`)
            VALUES (:id, :user_id, :name, :dimension_x, :dimension_y);'
        );

        $stmtGardenPlants = $this->prepare(
            'INSERT INTO `gardens_plants`
            (`garden_id`, `plant_id`, `coordinate_x`, `coordinate_y`)
            VALUES (:garden_id, :plant_id, :coordinate_x, :coordinate_y);'
        );

        $expectOneRowAffected = function ($rowCount){ return $rowCount !== 1; };

        $plantLocations = $garden->getPlantLocations();

        $this->execute(
            [
                'id' => $garden->getId(),
                'user_id' => $garden->getUserId(),
                'name' => $garden->getName(),
                'dimension_x' => $garden->getDimensionX(),
                'dimension_y' => $garden->getDimensionY(),
            ],
            $stmtGardens,
            $expectOneRowAffected
        );

        foreach($plantLocations as $plantLocation) {
            $this->execute(
                [
                    'garden_id' => $garden->getId(),
                    'plant_id' => $plantLocation->getPlantId(),
                    'coordinate_x' => $plantLocation->getCoordinateX(),
                    'coordinate_y' => $plantLocation->getCoordinateY(),
                ],
                $stmtGardenPlants,
                $expectOneRowAffected
            );
        }
    }

    /**
     * @param Garden $garden
     * @throws \Exception
     */
    public function updateUserGarden(Garden $garden): void
    {
        $stmt = $this->repositoryCollection->databaseConnection->dbh->prepare(
            'UPDATE `gardens`
            SET `name` = :name,
            `dimension_x` = :dimension_x,
            `dimension_y` = :dimension_y
            WHERE `id` = :id
            AND `user_id` = :user_id;'
        );

        if (!$stmt instanceOf \PDOStatement){
            throw new \Exception('Could not prepare database statement');
        }

        $stmt->execute([
           'id' => $garden->getId(),
           'user_id' => $garden->getUserId(),
           'name' => $garden->getName(),
           'dimension_x' => $garden->getDimensionX(),
           'dimension_y' => $garden->getDimensionY(),
        ]);

        /** @noinspection PhpNonStrictObjectEqualityInspection this is on purpose */
        if(
            $stmt->rowCount() < 1 &&
            $this->getUserGarden($garden->getUserId(), $garden->getId()) != $garden
        ){
            throw new NotFound();
        }

        if($stmt->rowCount() > 1){
            throw new \Exception('More than one database row was affected');
        }
    }

    /**
     * @param int $userId
     * @param string $gardenId
     * @return Garden
     * @throws NotFound
     * @throws OutOfRangeInt
     * @throws OverMaxChars
     * @throws UnderMinChars
     * @throws \Exception
     */
    public function getUserGarden(int $userId, string $gardenId): Garden
    {
        $stmt = $this->repositoryCollection->databaseConnection->dbh->prepare(
            'SELECT gardens.id as gardenId,
                gardens.user_id as gardenUserId,
                gardens.name,
                gardens.dimension_x,
                gardens.dimension_y,
                gardens_plants.coordinate_x,
                gardens_plants.coordinate_y,
                plants.id as plantId,
                plants.english_name,
                plants.latin_name,
                plants.image_link
            FROM `gardens`
            LEFT JOIN gardens_plants ON gardens.id=gardens_plants.garden_id
            LEFT JOIN plants ON gardens_plants.plant_id=plants.id
            WHERE gardens.user_id = :user_id
            AND gardens.id = :id;'
        );

        if (!$stmt instanceOf \PDOStatement){
            throw new \Exception('Could not prepare database statement');
        }

        $stmt->execute([
           'user_id' => $userId,
           'id' => $gardenId
        ]);

        $row = $stmt->fetch(\PDO::FETCH_OBJ);

        if(!$row){
            throw new NotFound();
        }

        $garden = new Garden(
            $row->gardenId,
            $row->gardenUserId,
            $row->name,
            $row->dimension_x,
            $row->dimension_y
        );

        while($row) {
            if ($row->plantId !== null){
                $plant = new Plant(
                    $row->plantId,
                    $row->gardenUserId,
                    $row->english_name,
                    $row->latin_name,
                    $row->image_link
                );

                $garden->setPlantLocation($plant,
                    $row->coordinate_x,
                    $row->coordinate_y
                );
            }
            $row = $stmt->fetch(\PDO::FETCH_OBJ);
        }

        return $garden;
    }
//
    /**
     * @param int $userId
     * @param string $gardenId
     * @throws NotFound
     * @throws \Exception
     */
    public function deleteUserGarden(int $userId, string $gardenId): void
    {
        $stmt = $this->repositoryCollection->databaseConnection->dbh->prepare(
            'DELETE
            FROM `gardens`
            WHERE `user_id` = :user_id
            AND `id` = :id;'
        );

        if (!$stmt instanceOf \PDOStatement){
            throw new \Exception('Could not prepare database statement');
        }

        $stmt->execute([
           'user_id' => $userId,
           'id' => $gardenId
        ]);

        if($stmt->rowCount() < 1){
            throw new NotFound();
        }

        if($stmt->rowCount() > 1){
            throw new \Exception('More than one database row was affected');
        }
    }
}