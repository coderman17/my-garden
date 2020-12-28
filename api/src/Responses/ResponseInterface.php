<?php

declare(strict_types = 1);

namespace MyGarden\Responses;

interface ResponseInterface
{
    public function getCode(): int;

    /**
     * @return mixed
     */
    public function getBody();
}