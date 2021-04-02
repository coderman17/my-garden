<?php

declare(strict_types = 1);

namespace MyGarden\Models;

use MyGarden\Exceptions\OverMaxChars;
use MyGarden\Exceptions\UnderMinChars;
use MyGarden\Interfaces\PropertyArrayInterface;

class Plant extends Model implements PropertyArrayInterface
{
    public const COLUMN_ALIASES = [
        'plants.id'             =>  'plantsId',
        'plants.user_id'        =>  'plantsUserId',
        'plants.english_name'   =>  'plantsEnglishName',
        'plants.latin_name'     =>  'plantsLatinName',
        'plants.image_link'     =>  'plantsImageLink'
    ];

    protected string $id;

    protected string $userId;

    protected string $englishName;

    protected string $latinName;

    protected string $imageLink;

    /**
     * @param string|null $id
     * @param string $userId
     * @param string $englishName
     * @param string $latinName
     * @param string $imageLink
     * @throws OverMaxChars
     * @throws UnderMinChars
     */
    public function __construct(?string $id, string $userId, string $englishName, string $latinName, string $imageLink)
    {
        if ($id !== null) {
            $this->validateParamStringLength('id', $id, Model::UUID_LENGTH, Model::UUID_LENGTH);

            $this->id = $id;
        } else {
            $this->id = uniqid('MG', true);
        }

        $this->validateParamStringLength('userId', $userId, Model::UUID_LENGTH, Model::UUID_LENGTH);

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

    public function getPropertyArray(): array
    {
        return [
            'id' => $this->getId(),
            'englishName' => $this->getEnglishName(),
            'latinName' => $this->getLatinName(),
            'imageLink' => $this->getImageLink(),
        ];
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}