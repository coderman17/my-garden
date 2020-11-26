<?php

declare(strict_types = 1);

namespace MyGarden\Models;

class Plant
{
    public ?int $id;

    protected int $userId;

    public string $englishName;

    public string $latinName;

    public string $imageLink;

    public function __construct(?int $id, int $userId, string $englishName, string $latinName, string $imageLink)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->englishName = $englishName;
        $this->latinName = $latinName;
        $this->imageLink = $imageLink;
    }
}