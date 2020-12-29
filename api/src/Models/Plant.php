<?php

declare(strict_types = 1);

namespace MyGarden\Models;

use MyGarden\Exceptions\OutOfRangeInt;
use MyGarden\Exceptions\OverMaxChars;
use MyGarden\Exceptions\UnderMinChars;

class Plant extends Model
{
    protected string $id;

    protected int $userId;

    protected string $englishName;

    protected string $latinName;

    protected string $imageLink;

    /**
     * @param string|null $id
     * @param int $userId
     * @param string $englishName
     * @param string $latinName
     * @param string $imageLink
     * @throws OutOfRangeInt
     * @throws OverMaxChars
     * @throws UnderMinChars
     */
    public function __construct(?string $id, int $userId, string $englishName, string $latinName, string $imageLink)
    {
        if ($id !== null) {
            $this->validateParamStringLength('id', $id, Model::UUID_LENGTH, Model::UUID_LENGTH);

            $this->id = $id;
        } else {
            $this->id = uniqid();
        }

        $this->validateParamIntRange('userId', $userId, 0, Model::UNSIGNED_INT_MAX);

        $this->userId = $userId;

        $this->validateParamStringLength('englishName', $englishName, 1, 80);

        $this->englishName = $englishName;

        $this->validateParamStringLength('latinName', $latinName, null, 255);

        $this->latinName = $latinName;

        $this->validateParamStringLength('imageLink', $imageLink, null, 500);

        $this->imageLink = $imageLink;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEnglishName(): string
    {
        return $this->englishName;
    }

    public function getLatinName(): string
    {
        return $this->latinName;
    }

    public function getImageLink(): string
    {
        return $this->imageLink;
    }

    public function mapJson(): array
    {
        return [
            'id' => $this->getId(),
            'englishName' => $this->getEnglishName(),
            'latinName' => $this->getLatinName(),
            'imageLink' => $this->getImageLink(),
        ];
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}