<?php

declare(strict_types = 1);

namespace MyGarden\Models;

use MyGarden\Exceptions\OutOfRangeInt;
use MyGarden\Exceptions\OverMaxChars;
use MyGarden\Exceptions\UnderMinChars;

class Plant extends Model
{
    //TODO make id uuid
    public ?int $id;

    protected int $userId;

    //TODO make all protected with setters which validate
    public string $englishName;

    public string $latinName;

    public string $imageLink;

    /**
     * @param int|null $id
     * @param int $userId
     * @param string $englishName
     * @param string $latinName
     * @param string $imageLink
     * @throws OutOfRangeInt
     * @throws OverMaxChars
     * @throws UnderMinChars
     */
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

    public function getId(): int
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
}