<?php

declare(strict_types = 1);

namespace MyGarden\Validators;

class PlantValidator extends Validator
{
    /**
     * @inheritDoc
     */
    protected function getIdFieldType(): array
    {
        return [
            'id' => [
                'type' => 'string',
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getNonIdFieldTypes(): array
    {
        return [
            'englishName' => [
                'type' => 'string',
            ],
            'latinName' => [
                'type' => 'string',
            ],
            'imageLink' => [
                'type' => 'string',
            ],
        ];
    }
}