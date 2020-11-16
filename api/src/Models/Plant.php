<?php

declare(strict_types = 1);

namespace MyGarden\Models;

class Plant
{
    protected ?int $id;

    protected int $userId;

    protected string $englishName;

    protected ?string $latinName;

    public function __construct(?int $id, int $userId, string $englishName, ?string $latinName)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->englishName = $englishName;
        $this->latinName = $latinName;
    }

    public function getEnglishName(): string
    {
        return $this->englishName;
    }
}