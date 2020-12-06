<?php

declare(strict_types = 1);

namespace MyGarden\Models;

class Plant extends Model
{
    public ?int $id;

    protected int $userId;

    public string $englishName;

    public string $latinName;

    public string $imageLink;

    public function __construct(?int $id, int $userId, string $englishName, string $latinName, string $imageLink)
    {
        if ($id !== null) {
            $this->validateParamIntRange('id', $id, 0, Model::UNSIGNED_INT_MAX);
        }

        $this->id = $id;

        $this->validateParamIntRange('userId', $userId, 0, Model::UNSIGNED_INT_MAX);

        $this->userId = $userId;

        $this->validateParamStringLength('englishName', $englishName, 1, 80);

        $this->englishName = $englishName;

        $this->validateParamStringLength('latinName', $latinName, null, 255);

        $this->latinName = $latinName;

        $this->validateParamStringLength('imageLink', $imageLink, null, 500);

        $this->imageLink = $imageLink;
    }
}