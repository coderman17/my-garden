<?php

declare(strict_types = 1);

namespace MyGarden\Repositories;

use MyGarden\Exceptions\NotFound;
use MyGarden\Exceptions\OutOfRangeInt;
use MyGarden\Exceptions\OverMaxChars;
use MyGarden\Exceptions\UnderMinChars;
use MyGarden\Models\Plant;
use MyGarden\TypedArrays\IntToPlantArray;

class PlantRepository extends Repository
{

    /**
     * @param int $userId
     * @return IntToPlantArray
     * @throws OutOfRangeInt
     * @throws OverMaxChars
     * @throws UnderMinChars
     * @throws \Exception
     */
    public function getUserPlants(int $userId): IntToPlantArray
    {
        $intToPlantArray = new IntToPlantArray();

        $stmt = $this->repositoryCollection->databaseConnection->dbh->prepare(
            'SELECT *
            FROM `plants`
            WHERE `user_id` = :user_id;'
        );

        if (!$stmt instanceOf \PDOStatement){
            throw new \Exception('Could not prepare database statement');
        }

        $stmt->execute([
           'user_id' => $userId
       ]);

        while($row = $stmt->fetch(\PDO::FETCH_OBJ)) {
            $plant = new Plant(
                $row->id,
                $row->user_id,
                $row->english_name,
                $row->latin_name,
                $row->image_link
            );
            $intToPlantArray->pushItem($plant);
        }

        return $intToPlantArray;
    }

    /**
     * @param int $userId
     * @param Plant $plant
     * @return Plant
     * @throws \Exception
     */
    public function saveUserPlant(int $userId, Plant $plant): Plant
    {
        $stmt = $this->repositoryCollection->databaseConnection->dbh->prepare(
            'INSERT INTO `plants`
            (`user_id`, `english_name`, `latin_name`, `image_link`)
            VALUES (:user_id, :english_name, :latin_name, :image_link);'
        );

        if (!$stmt instanceOf \PDOStatement){
            throw new \Exception('Could not prepare database statement');
        }

        $stmt->execute(
            [
                'user_id' => $userId,
                'english_name' => $plant->englishName,
                'latin_name' => $plant->latinName,
                'image_link' => $plant->imageLink,
            ]
        );


        if($stmt->rowCount() !== 1){
            throw new \Exception('Expected 1 row to be counted');
        }

        $id = $this->repositoryCollection->databaseConnection->dbh->lastInsertId();

        $plant->id = intval($id);

        return $plant;
    }

    /**
     * @param int $userId
     * @param Plant $plant
     * @return Plant
     * @throws \Exception
     */
    public function updateUserPlant(int $userId, Plant $plant): Plant
    {
        $stmt = $this->repositoryCollection->databaseConnection->dbh->prepare(
            'UPDATE `plants`
            SET `english_name` = :english_name,
            `latin_name` = :latin_name,
            `image_link` = :image_link
            WHERE `id` = :id
            AND `user_id` = :user_id;'
        );

        if (!$stmt instanceOf \PDOStatement){
            throw new \Exception('Could not prepare database statement');
        }

        $stmt->execute([
            'id' => $plant->id,
            'user_id' => $userId,
            'english_name' => $plant->englishName,
            'latin_name' => $plant->latinName,
            'image_link' => $plant->imageLink,
        ]);

        return $plant;
    }

    /**
     * @param int $userId
     * @param int $plantId
     * @return Plant
     * @throws NotFound
     * @throws OutOfRangeInt
     * @throws OverMaxChars
     * @throws UnderMinChars
     * @throws \Exception
     */
    public function getUserPlant(int $userId, int $plantId): Plant
    {
        $stmt = $this->repositoryCollection->databaseConnection->dbh->prepare(
            'SELECT *
            FROM `plants`
            WHERE `user_id` = :user_id
            AND `id` = :id;'
        );

        if (!$stmt instanceOf \PDOStatement){
            throw new \Exception('Could not prepare database statement');
        }

        $stmt->execute([
           'user_id' => $userId,
           'id' => $plantId
        ]);

        $row = $stmt->fetch(\PDO::FETCH_OBJ);

        if(!$row){
            throw new NotFound();
        }

        return new Plant(
            $row->id,
            $row->user_id,
            $row->english_name,
            $row->latin_name,
            $row->image_link
        );
    }

    /**
     * @param int $userId
     * @param int $plantId
     * @throws NotFound
     * @throws \Exception
     */
    public function deleteUserPlant(int $userId, int $plantId): void
    {
        $stmt = $this->repositoryCollection->databaseConnection->dbh->prepare(
            'DELETE
            FROM `plants`
            WHERE `user_id` = :user_id
            AND `id` = :id;'
        );

        if (!$stmt instanceOf \PDOStatement){
            throw new \Exception('Could not prepare database statement');
        }

        $stmt->execute([
           'user_id' => $userId,
           'id' => $plantId
        ]);

        if($stmt->rowCount() !== 1){
            throw new NotFound();
        }
    }
}