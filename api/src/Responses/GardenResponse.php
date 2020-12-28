<?php

declare(strict_types = 1);

namespace MyGarden\Responses;

use MyGarden\Models\Garden;
use MyGarden\TypedArrays\IntToGardenArray;

class GardenResponse implements ResponseInterface
{
    protected int $code;

    /**
     * @var mixed
     */
    protected $body;

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

    public function setSingleResponse(Garden $garden): void
    {
        $this->body = $this->mapJson($garden);
    }

    public function setCollectionResponse(IntToGardenArray $intToGardenArray): void
    {
        foreach ($intToGardenArray as $garden){
            array_push(
                $this->body,
                $this->mapJson($garden)
            );
        }
    }

    protected function mapJson(Garden $garden): array
    {
        return [
            'id' => $garden->getId(),
            'name' => $garden->getName(),
            'dimensionX' => $garden->getDimensionX(),
            'dimensionY' => $garden->getDimensionY(),
        ];
    }
}