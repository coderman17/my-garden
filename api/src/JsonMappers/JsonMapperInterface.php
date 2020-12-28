<?php

declare(strict_types = 1);

namespace MyGarden\JsonMappers;

interface JsonMapperInterface
{
    public function mapJson($model);
}