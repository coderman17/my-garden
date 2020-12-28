<?php

declare(strict_types = 1);

namespace MyGarden\Responses;

use MyGarden\Models\Plant;
use MyGarden\TypedArrays\IntToPlantArray;

class PlantResponse implements ResponseInterface
{
    protected int $code;

    /**
     * @var array<string, int|string>|array<array<string, int|string>>
     */
    protected array $body = [];

    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @inheritDoc
     */
    public function getBody(): array
    {
        return $this->body;
    }

    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    public function setBodySingleResource(Plant $plant): void
    {
        $this->body = $this->mapJson($plant);
    }

    public function setBodyCollectionResource(IntToPlantArray $array): void
    {
        foreach ($array as $plant){
            array_push(
                $this->body,
                $this->mapJson($plant)
            );
        }
    }

    /**
     * @param Plant $plant
     * @return array<string, int|string>
     */
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