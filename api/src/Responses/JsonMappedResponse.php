<?php

declare(strict_types = 1);

namespace MyGarden\Responses;

use MyGarden\Interfaces\PropertyArrayInterface;
use MyGarden\Interfaces\ResponseInterface;

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

    public function setBodySingleResource(PropertyArrayInterface $resource): void
    {
        $this->body = $resource->getPropertyArray();
    }

    /**
     * @inheritDoc
     */
    public function setBodyCollectionResource(array $array): void
    {
        foreach ($array as $model){
            $this->body[] = $model->getPropertyArray();
        }
    }
}