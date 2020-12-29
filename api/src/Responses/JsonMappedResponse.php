<?php

declare(strict_types = 1);

namespace MyGarden\Responses;

use MyGarden\Models\Model;
use TypedArrays\IntToValueArrays\IntToClassArray;

class JsonMappedResponse implements ResponseInterface
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

    public function setBodySingleResource(Model $model): void
    {
        $this->body = $model->mapJson();
    }

    public function setBodyCollectionResource(IntToClassArray $array): void
    {
        foreach ($array as $model){
            array_push(
                $this->body,
                $model->mapJson()
            );
        }
    }
}