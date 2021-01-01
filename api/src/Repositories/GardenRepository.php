<?php

declare(strict_types = 1);

namespace MyGarden\Repositories;

use MyGarden\Exceptions\NotFound;
use MyGarden\Exceptions\OutOfRangeInt;
use MyGarden\Exceptions\OverMaxChars;
use MyGarden\Exceptions\UnderMinChars;
use MyGarden\Models\Garden;
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
        $stmt = $this->repositoryCollection->databaseConnection->dbh->prepare(
            'INSERT INTO `gardens`
            (`id`, `user_id`, `name`, `dimension_x`, `dimension_y`)
            VALUES (:id, :user_id, :name, :dimension_x, :dimension_y);'
        );

        if (!$stmt instanceOf \PDOStatement){
            throw new \Exception('Could not prepare database statement');
        }

        $stmt->execute(
            [
                'id' => $garden->getId(),
                'user_id' => $garden->getUserId(),
                'name' => $garden->getName(),
                'dimension_x' => $garden->getDimensionX(),
                'dimension_y' => $garden->getDimensionY(),
            ]
        );

        if($stmt->rowCount() !== 1){
            throw new \Exception('An unexpected number of database rows were affected');
        }
    }

    /**
     * @param Garden $garden
     * @throws \Exception
     */
    public function saveUserGardenPlants(Garden $garden): void
    {
        //merge with saveUserGarden and keep prep and exec. separate
        $stmt = $this->repositoryCollection->databaseConnection->dbh->prepare(
            'INSERT INTO `gardens_plants`
            (`garden_id`, `plant_id`, `coordinate_x`, `coordinate_y`)
            VALUES (:garden_id, :plant_id, :coordinate_x, :coordinate_y);'
        );

        if (!$stmt instanceOf \PDOStatement){
            throw new \Exception('Could not prepare database statement');
        }

        $plantLocations = $garden->getPlantLocations();

        foreach($plantLocations as $plantLocation) {
            $stmt->execute(
                [
                    'garden_id' => $garden->getId(),
                    'plant_id' => $plantLocation->getPlantId(),
                    'coordinate_x' => $plantLocation->getCoordinateX(),
                    'coordinate_y' => $plantLocation->getCoordinateY(),
                ]
            );

            if($stmt->rowCount() !== 1){
                throw new \Exception('An unexpected number of database rows were affected');
            }
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
            'SELECT *
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

        $row = $stmt->fetch(\PDO::FETCH_OBJ);

        if(!$row){
            throw new NotFound();
        }

        return new Garden(
            $row->id,
            $row->user_id,
            $row->name,
            $row->dimension_x,
            $row->dimension_y
        );
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