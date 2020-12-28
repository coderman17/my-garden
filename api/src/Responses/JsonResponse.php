<?php

declare(strict_types = 1);

namespace MyGarden\Responses;

use JsonMapper;
use MyGarden\Models\Model;
use TypedArrays\IntToValueArrays\IntToClassArray;

class JsonResponse implements ResponseInterface
{
    protected int $code;

    /**
     * @var array<string, int|string>|array<array<string, int|string>>
     */
    protected array $body = [];

    protected JsonMapper $jsonMapper;

    public function __construct(JsonMapper $jsonMapper)
    {
        $this->jsonMapper = $jsonMapper;
    }

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
        $this->body = $this->mapJson($model);
    }

    public function setBodyCollectionResource(IntToClassArray $array): void
    {
        foreach ($array as $plant){
            array_push(
                $this->body,
                $this->mapJson($plant)
            );
        }
    }

    /**
     * @param Model $model
     * @return array<string, int|string>
     */
    protected function mapJson(Model $model): array
    {
        return $this->jsonMapper->mapJson($model);
    }
}