<?php

declare(strict_types=1);

namespace MyGarden\Validators;

use MyGarden\Exceptions\MissingParameter;
use MyGarden\Exceptions\WrongTypeParameter;
use MyGarden\Request\Request;

abstract class Validator
{
    /**
     * @param  Request $request
     * @throws MissingParameter
     * @throws WrongTypeParameter
     */
    public function validateRequestWithId(Request $request): void
    {
        $this->recursiveParamChecker(
            array_merge($this->getIdFieldType(), $this->getNonIdFieldTypes()),
            $request->params
        );
    }

    /**
     * @param  Request $request
     * @throws MissingParameter
     * @throws WrongTypeParameter
     */
    public function validateRequestWithoutId(Request $request): void
    {
        $this->recursiveParamChecker($this->getNonIdFieldTypes(), $request->params);
    }

    /**
     * @param  Request $request
     * @throws MissingParameter
     * @throws WrongTypeParameter
     */
    public function validateRequestId(Request $request): void
    {
        $this->recursiveParamChecker($this->getIdFieldType(), $request->params);
    }

    /**
     * @param  array<string, array> $specification
     * @param  array<string, mixed> $requestParams
     * @throws MissingParameter
     * @throws WrongTypeParameter
     */
    protected function recursiveParamChecker(array $specification, array $requestParams): void
    {
        foreach ($specification as $keyName => $attributes) {
            if (!isset($requestParams[$keyName])) {
                if ($attributes['optional'] === true) {
                    continue;
                }
                throw new MissingParameter($keyName);
            }

            if (gettype($requestParams[$keyName]) !== $attributes['type']) {
                throw new WrongTypeParameter($keyName, $attributes['type']);
            }

            if ($attributes['type'] === 'array') {
                if ($attributes['arrayType'] === 'indexed') {
                    //If the array is indexed, the spec only needs to list types for the first element.
                    //This foreach uses the spec for that first element to check every subsequent element in the array
                    foreach ($requestParams[$keyName] as $item) {
                        if (!is_array($item)) {
                            throw new WrongTypeParameter($keyName, $attributes['type']);
                        }

                        $this->recursiveParamChecker($attributes['contents'][0]['contents'], $item);
                    }
                } elseif ($attributes['arrayType'] === 'associative') {
                    //If the array is associative, then nothing is assumed and every element in the array
                    //is expected to be listed in the spec
                    $this->recursiveParamChecker($attributes['contents'], $requestParams[$keyName]);
                }
            }
        }
    }

    /**
     * @return array<string, array>
     */
    abstract protected function getIdFieldType(): array;

    /**
     * @return array<string, array>
     */
    abstract protected function getNonIdFieldTypes(): array;
}
