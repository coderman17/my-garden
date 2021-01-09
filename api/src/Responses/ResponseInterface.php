<?php

declare(strict_types = 1);

namespace MyGarden\Responses;

use MyGarden\Models\Model;

interface ResponseInterface
{
    public function setCode(int $code): void;

    public function getCode(): int;

    public function setBodySingleResource(Model $model): void;

    /**
     * @param array<mixed, Model> $array
     */
    public function setBodyCollectionResource(array $array): void;

    /**
     * @return array<string, int|string>|array<array<string, int|string>>
     */
    public function getBody(): array;
}