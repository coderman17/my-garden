<?php

declare(strict_types = 1);

namespace MyGarden\Responses;

use MyGarden\Models\Model;
use TypedArrays\IntToValueArrays\IntToClassArray;
use TypedArrays\StringToValueArrays\StringToClassArray;

class JsonMappedResponse implements ResponseInterface
{
    protected int $code = 0;

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

    public function setBodySingleResource(Model $model): void
    {
        $this->body = $model->mapJson();
    }

    /**
     * @inheritDoc
     */
    public function setBodyCollectionResource(array $array): void
    {
        foreach ($array as $model){
            array_push(
                $this->body,
                $model->mapJson()
            );
        }
    }
}