<?php

declare(strict_types = 1);

namespace MyGarden\Repositories;

use MyGarden\Exceptions\ConstructionFailure;
use MyGarden\Exceptions\NotFound;
use MyGarden\Models\Garden;
use MyGarden\Models\Plant;
use MyGarden\TypedArrays\IntToGardenArray;

class GardenRepository extends Repository
{

    /**
     * @param int $userId
     * @return IntToGardenArray
     * @throws \Exception
     * @throws ConstructionFailure
     * @throws \InvalidArgumentException
     */
    public function getUserGardens(int $userId): IntToGardenArray
    {
        $intToGardenArray = new IntToGardenArray();

        $stmt = $this->prepare(
            'SELECT
                gardens.id as gardenId,
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
            FROM
                `gardens`
            LEFT JOIN
                gardens_plants
                ON gardens.id=gardens_plants.garden_id
            LEFT JOIN
                plants
                ON gardens_plants.plant_id=plants.id
            WHERE
                gardens.user_id = :user_id
            ORDER BY
                gardenId, gardens_plants.coordinate_x, gardens_plants.coordinate_y;'
        );

        $this->execute(
            [
           'user_id' => $userId
            ],
            $stmt,
            function (){ return false; }
        );

        $previousGardenId = null;

        $garden = null;

        while($row = $stmt->fetch(\PDO::FETCH_OBJ)) {
            if($row->gardenId !== $previousGardenId) {
                if($garden !== null){
                    $intToGardenArray->pushItem($garden);
                }

                try {
                    $garden = new Garden(
                        $row->gardenId,
                        $row->gardenUserId,
                        $row->name,
                        $row->dimension_x,
                        $row->dimension_y
                    );

                } catch (\Exception $e){
                    throw new ConstructionFailure($e);
                }
            }

            if ($row->plantId !== null){
                try {
                    $plant = new Plant(
                        $row->plantId,
                        $row->gardenUserId,
                        $row->english_name,
                        $row->latin_name,
                        $row->image_link
                    );

                    $garden->setPlantLocation(
                        $plant,
                        $row->coordinate_x,
                        $row->coordinate_y
                    );

                } catch (\Exception $e){
                    throw new ConstructionFailure($e);
                }
            }

            $previousGardenId = $garden->getId();
        }

        $intToGardenArray->pushItem($garden);

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
            function ($rowCount){ return $rowCount !== 1; }
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
                function ($rowCount){ return $rowCount !== 1; }
            );
        }
    }

    /**
     * @param Garden $garden
     * @throws \Exception
     * @throws NotFound
     */
    public function updateUserGarden(Garden $garden): void
    {
        $stmt = $this->prepare(
            'UPDATE `gardens`
            SET `name` = :name,
                `dimension_x` = :dimension_x,
                `dimension_y` = :dimension_y
            WHERE `id` = :id
            AND `user_id` = :user_id;'
        );

        $this->execute(
            [
                'id' => $garden->getId(),
                'user_id' => $garden->getUserId(),
                'name' => $garden->getName(),
                'dimension_x' => $garden->getDimensionX(),
                'dimension_y' => $garden->getDimensionY(),
            ],
            $stmt,
            function ($rowCount){ return $rowCount > 1; }
        );

        if(
            $stmt->rowCount() < 1
        ){
            //throws NotFound
            $this->getUserGarden($garden->getUserId(), $garden->getId());
        }

        $stmt = $this->prepare(
            'DELETE FROM `gardens_plants`
            WHERE `garden_id` = :garden_id;'
        );

        $this->execute(
            [
                'garden_id' => $garden->getId(),
            ],
            $stmt,
            function (){ return false; }
        );

        $stmtGardenPlants = $this->prepare(
            'INSERT INTO `gardens_plants`
            (`garden_id`, `plant_id`, `coordinate_x`, `coordinate_y`)
            VALUES (:garden_id, :plant_id, :coordinate_x, :coordinate_y);'
        );

        $plantLocations = $garden->getPlantLocations();

        foreach($plantLocations as $plantLocation) {
            $this->execute(
                [
                    'garden_id' => $garden->getId(),
                    'plant_id' => $plantLocation->getPlantId(),
                    'coordinate_x' => $plantLocation->getCoordinateX(),
                    'coordinate_y' => $plantLocation->getCoordinateY(),
                ],
                $stmtGardenPlants,
                function ($rowCount){ return $rowCount !== 1; }
            );
        }
    }

    /**
     * @param int $userId
     * @param string $gardenId
     * @return Garden
     * @throws NotFound
     * @throws \Exception
     * @throws ConstructionFailure
     */
    public function getUserGarden(int $userId, string $gardenId): Garden
    {
        $stmt = $this->prepare(
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

        $this->execute(
            [
               'user_id' => $userId,
               'id' => $gardenId
            ],
            $stmt,
            function (){ return false; }
        );

        $row = $stmt->fetch(\PDO::FETCH_OBJ);

        if(!$row){
            throw new NotFound($gardenId);
        }

        try {
            $garden = new Garden(
                $row->gardenId,
                $row->gardenUserId,
                $row->name,
                $row->dimension_x,
                $row->dimension_y
            );

        } catch (\Exception $e){
            throw new ConstructionFailure($e);
        }

        while($row) {
            if ($row->plantId !== null){
                try {
                    $plant = new Plant(
                        $row->plantId,
                        $row->gardenUserId,
                        $row->english_name,
                        $row->latin_name,
                        $row->image_link
                    );

                    $garden->setPlantLocation(
                        $plant,
                        $row->coordinate_x,
                        $row->coordinate_y
                    );

                } catch (\Exception $e){
                    throw new ConstructionFailure($e);
                }
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
        $stmt = $this->prepare(
            'DELETE
            FROM `gardens`
            WHERE `user_id` = :user_id
            AND `id` = :id;'
        );

        $this->execute(
            [
               'user_id' => $userId,
               'id' => $gardenId
            ],
            $stmt,
            function ($rowCount){ return $rowCount > 1; }
        );

        if($stmt->rowCount() < 1){
            throw new NotFound($gardenId);
        }
    }
}