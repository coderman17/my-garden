<?php

declare(strict_types = 1);

namespace MyGarden\Repositories;

use MyGarden\Models\Plant;
use MyGarden\TypedArrays\IntToPlantArray;

class PlantRepository extends Repository
{
    public function getUserPlants(int $userId): IntToPlantArray
    {
        $intToPlantArray = new IntToPlantArray();

        $stmt = $this->repositoryCollection->databaseConnection->dbh->prepare(
            "SELECT *
            FROM `plants`
            WHERE `user_id` = :user_id;"
        );

        if (!$stmt){
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
                $row->latin_name
            );
            $intToPlantArray->pushItem($plant);
        }

        return $intToPlantArray;
    }

    public function saveUserPlant(int $userId, Plant $plant): Plant
    {
        $stmt = $this->repositoryCollection->databaseConnection->dbh->prepare(
            "INSERT INTO `plants`
            (`user_id`, `english_name`, `latin_name`)
            VALUES (:user_id, :english_name, :latin_name);"
        );

        if (!$stmt){
            throw new \Exception('Could not prepare database statement');
        }

        $stmt->execute([
            'user_id' => $userId,
            'english_name' => $plant->englishName,
            'latin_name' => $plant->latinName,
        ]);

        $id = $this->repositoryCollection->databaseConnection->dbh->lastInsertId();

        $plant->id = intval($id);

        return $plant;
    }

    public function updateUserPlant(int $userId, Plant $plant): Plant
    {
        $stmt = $this->repositoryCollection->databaseConnection->dbh->prepare(
            "UPDATE `plants`
            SET `english_name` = :english_name,
            `latin_name` = :latin_name
            WHERE `id` = :id
            AND `user_id` = :user_id;"
        );

        if (!$stmt){
            throw new \Exception('Could not prepare database statement');
        }

        $stmt->execute([
            'id' => $plant->id,
            'user_id' => $userId,
            'english_name' => $plant->englishName,
            'latin_name' => $plant->latinName,
        ]);

        return $plant;
    }

    public function getUserPlant(int $userId, int $plantId): Plant
    {
        $stmt = $this->repositoryCollection->databaseConnection->dbh->prepare(
            "SELECT *
            FROM `plants`
            WHERE `user_id` = :user_id
            AND `id` = :id;"
        );

        if (!$stmt){
            throw new \Exception('Could not prepare database statement');
        }

        $stmt->execute([
           'user_id' => $userId,
           'id' => $plantId
        ]);

        $row = $stmt->fetch(\PDO::FETCH_OBJ);

        if(!$row){
            throw new \Exception('Could find a plant of that id belonging to the user');
        }

        $plant = new Plant(
            $row->id,
            $row->user_id,
            $row->english_name,
            $row->latin_name
        );

        return $plant;
    }

    public function deleteUserPlant(int $userId, int $plantId): void
    {
        $stmt = $this->repositoryCollection->databaseConnection->dbh->prepare(
            "DELETE
            FROM `plants`
            WHERE `user_id` = :user_id
            AND `id` = :id;"
        );

        if (!$stmt){
            throw new \Exception('Could not prepare database statement');
        }

        $stmt->execute([
           'user_id' => $userId,
           'id' => $plantId
        ]);

        if($stmt->rowCount() !== 1){
            throw new \Exception('Could not delete a plant of that id belonging to the user');
        }
    }
}