<?php

declare(strict_types = 1);

namespace MyGarden\Interfaces;

interface PropertyArrayInterface
{
    /**
     * Returns an array of object properties for public consumption
     *
     * @return array<string, mixed>
     */
    public function getPropertyArray(): array;
}