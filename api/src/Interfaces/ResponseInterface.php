<?php

declare(strict_types = 1);

namespace MyGarden\Interfaces;

interface ResponseInterface
{
    public function setCode(int $code): void;

    public function getCode(): int;

    public function setBodySingleResource(PropertyArrayInterface $resource): void;

    /**
     * @param array<mixed, PropertyArrayInterface> $array
     */
    public function setBodyCollectionResource(array $array): void;

    /**
     * @return array<string, mixed>|array<array<string, mixed>>
     */
    public function getBody(): array;
}