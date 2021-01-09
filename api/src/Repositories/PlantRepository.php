<?php

declare(strict_types = 1);

namespace MyGarden\Repositories;

use MyGarden\Exceptions\ConstructionFailure;
use MyGarden\Exceptions\NotFound;
use MyGarden\Models\Plant;
use MyGarden\TypedArrays\IntToPlantArray;

class PlantRepository extends Repository
{

    /**
     * @param string $userId
     * @return IntToPlantArray
     * @throws \Exception
     * @throws \InvalidArgumentException
     * @throws ConstructionFailure
     */
    public function getUserPlants(string $userId): IntToPlantArray
    {
        $intToPlantArray = new IntToPlantArray();

        $stmt = $this->prepare(
            'SELECT *
            FROM `plants`
            WHERE `user_id` = :user_id;'
        );

        $this->execute(
            [
           'user_id' => $userId
            ],
            $stmt,
            function (){ return false; }
        );

        while($row = $stmt->fetch(\PDO::FETCH_OBJ)) {
            try {
                $plant = new Plant(
                    $row->id,
                    $row->user_id,
                    $row->english_name,
                    $row->latin_name,
                    $row->image_link
                );

            } catch (\Exception $e){
                throw new ConstructionFailure($e);
            }

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
        $stmt = $this->prepare(
            'INSERT INTO `plants`
            (`id`, `user_id`, `english_name`, `latin_name`, `image_link`)
            VALUES (:id, :user_id, :english_name, :latin_name, :image_link);'
        );

        $this->execute(
            [
                'id' => $plant->getId(),
                'user_id' => $plant->getUserId(),
                'english_name' => $plant->getEnglishName(),
                'latin_name' => $plant->getLatinName(),
                'image_link' => $plant->getImageLink(),
            ],
            $stmt,
            function ($rowCount){ return $rowCount !== 1; }
        );
    }

    /**
     * @param Plant $plant
     * @throws \Exception
     * @throws NotFound
     */
    public function updateUserPlant(Plant $plant): void
    {
        $stmt = $this->prepare(
            'UPDATE `plants`
            SET `english_name` = :english_name,
            `latin_name` = :latin_name,
            `image_link` = :image_link
            WHERE `id` = :id
            AND `user_id` = :user_id;'
        );

        $this->execute(
            [
            'id' => $plant->getId(),
            'user_id' => $plant->getUserId(),
            'english_name' => $plant->getEnglishName(),
            'latin_name' => $plant->getLatinName(),
            'image_link' => $plant->getImageLink(),
            ],
            $stmt,
            function ($rowCount){ return $rowCount > 1; }
        );

        if(
            $stmt->rowCount() < 1
        ){
            //throws Not Found
            $this->getUserPlant($plant->getUserId(), $plant->getId());
        }
    }

    /**
     * @param string $userId
     * @param string $plantId
     * @return Plant
     * @throws NotFound
     * @throws \Exception
     * @throws ConstructionFailure
     */
    public function getUserPlant(string $userId, string $plantId): Plant
    {
        $stmt = $this->prepare(
            'SELECT *
            FROM `plants`
            WHERE `user_id` = :user_id
            AND `id` = :id;'
        );

        $this->execute(
            [
           'user_id' => $userId,
           'id' => $plantId
            ],
            $stmt,
            function ($rowCount){ return $rowCount > 1; }
        );

        $row = $stmt->fetch(\PDO::FETCH_OBJ);

        if(!$row){
            throw new NotFound($plantId);
        }

        try {
            $plant = new Plant(
                $row->id,
                $row->user_id,
                $row->english_name,
                $row->latin_name,
                $row->image_link
            );
        } catch (\Exception $e){
            throw new ConstructionFailure($e);
        }

        return $plant;
    }

    /**
     * @param string $userId
     * @param string $plantId
     * @throws NotFound
     * @throws \Exception
     */
    public function deleteUserPlant(string $userId, string $plantId): void
    {
        $stmt = $this->prepare(
            'DELETE
            FROM `plants`
            WHERE `user_id` = :user_id
            AND `id` = :id;'
        );

        $this->execute(
            [
               'user_id' => $userId,
               'id' => $plantId
            ],
            $stmt,
            function ($rowCount){ return $rowCount > 1; }
        );

        if($stmt->rowCount() < 1){
            throw new NotFound($plantId);
        }
    }
}