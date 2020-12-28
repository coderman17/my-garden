<?php

declare(strict_types = 1);

namespace MyGarden\Responses;

use MyGarden\Models\Plant;
use MyGarden\TypedArrays\IntToPlantArray;

class PlantResponse implements ResponseInterface
{
    protected int $code;

    /**
     * @var mixed
     */
    protected $body = [];

    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @inheritDoc
     */
    public function getBody()
    {
        return $this->body;
    }

    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    public function setSingleResponse(Plant $plant): void
    {
        $this->body = $this->mapJson($plant);
    }

    public function setCollectionResponse(IntToPlantArray $intToPlantArray): void
    {
        foreach ($intToPlantArray as $plant){
            array_push(
                $this->body,
                $this->mapJson($plant)
            );
        }
    }

    protected function mapJson(Plant $plant): array
    {
        return [
            'id' => $plant->getId(),
            'englishName' => $plant->getEnglishName(),
            'latinName' => $plant->getLatinName(),
            'imageLink' => $plant->getImageLink(),
        ];
    }
}