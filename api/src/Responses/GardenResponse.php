<?php

declare(strict_types = 1);

namespace MyGarden\Responses;

use MyGarden\Models\Garden;
use MyGarden\TypedArrays\IntToGardenArray;

class GardenResponse implements ResponseInterface
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

    public function setBodySingleResource(Garden $garden): void
    {
        $this->body = $this->mapJson($garden);
    }

    public function setBodyCollectionResource(IntToGardenArray $array): void
    {
        foreach ($array as $garden){
            array_push(
                $this->body,
                $this->mapJson($garden)
            );
        }
    }

    /**
     * @param Garden $garden
     * @return array<string, int|string>
     */
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