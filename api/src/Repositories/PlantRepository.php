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
     * @param Plant $plant
     * @throws \Exception
     */
    public function saveUserPlant(Plant $plant): void
    {
        $stmt = $this->repositoryCollection->databaseConnection->dbh->prepare(
            'INSERT INTO `plants`
            (`id`, `user_id`, `english_name`, `latin_name`, `image_link`)
            VALUES (:id, :user_id, :english_name, :latin_name, :image_link);'
        );

        if (!$stmt instanceOf \PDOStatement){
            throw new \Exception('Could not prepare database statement');
        }

        $stmt->execute(
            [
                'id' => $plant->getId(),
                'user_id' => $plant->getUserId(),
                'english_name' => $plant->getEnglishName(),
                'latin_name' => $plant->getLatinName(),
                'image_link' => $plant->getImageLink(),
            ]
        );

        if($stmt->rowCount() !== 1){
            throw new \Exception('An unexpected number of database rows were affected');
        }
    }

    /**
     * @param Plant $plant
     * @throws \Exception
     * @throws NotFound
     */
    public function updateUserPlant(Plant $plant): void
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
            throw new \Exception('Could not prepare database statement: ' . $this->repositoryCollection->databaseConnection->dbh->errorInfo()[2]);
        }

        $stmt->execute([
            'id' => $plant->getId(),
            'user_id' => $plant->getUserId(),
            'english_name' => $plant->getEnglishName(),
            'latin_name' => $plant->getLatinName(),
            'image_link' => $plant->getImageLink(),
        ]);

        if(
            $stmt->rowCount() < 1
        ){
            //throws Not Found
            $this->getUserPlant($plant->getUserId(), $plant->getId());
        }

        if($stmt->rowCount() > 1){
            throw new \Exception('More than one database row was affected');
        }
    }

    /**
     * @param int $userId
     * @param string $plantId
     * @return Plant
     * @throws NotFound
     * @throws OutOfRangeInt
     * @throws OverMaxChars
     * @throws UnderMinChars
     * @throws \Exception
     */
    public function getUserPlant(int $userId, string $plantId): Plant
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
            throw new NotFound($plantId);
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
     * @param string $plantId
     * @throws NotFound
     * @throws \Exception
     */
    public function deleteUserPlant(int $userId, string $plantId): void
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

        if($stmt->rowCount() < 1){
            throw new NotFound($plantId);
        }

        if($stmt->rowCount() > 1){
            throw new \Exception('More than one database row was affected');
        }
    }
}