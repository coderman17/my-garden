<?php

declare(strict_types = 1);

namespace MyGarden\Models;

class Plant
{
    public ?int $id;

    protected int $userId;

    public string $englishName;

    public string $latinName;

    public function __construct(?int $id, int $userId, string $englishName, string $latinName)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->englishName = $englishName;
        $this->latinName = $latinName;
    }
}