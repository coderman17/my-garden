<?php

declare(strict_types = 1);

namespace MyGarden\Validators;

class GardenValidator extends Validator
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
            'name' => [
                'type' => 'string',
            ],
            'dimensionX' => [
                'type' => 'integer',
            ],
            'dimensionY' => [
                'type' => 'integer',
            ],
            'plants' => [
                'type' => 'array',
                'optional' => true,
                'arrayType' => 'indexed',
                'contents' => [
                    0 => [
                        'type' => 'array',
                        'arrayType' => 'associative',
                        'contents' => [
                            'id' => [
                                'type' => 'string',
                            ],
                            'coordinateX' => [
                                'type' => 'integer',
                            ],
                            'coordinateY' => [
                                'type' => 'integer',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}