<?php

declare(strict_types=1);

namespace MyGarden;

class Route
{
    private string $controllerName;

    private string $methodName;

    /**
     * @throws \Exception
     */
    public function __construct(string $controllerName, string $methodName)
    {
        $this->validate($controllerName, $methodName);

        $this->controllerName = $controllerName;

        $this->methodName = $methodName;
    }

    /**
     * @param string $controllerName
     * @param string $methodName
     * @throws \Exception
     */
    private function validate(string $controllerName, string $methodName): void
    {
        try {
            //TODO check performance of reflection
            new \ReflectionMethod($controllerName, $methodName);
        } catch (\Throwable $e) {
            throw new \Exception(
                'Invalid Route: Could not find controller ' . $controllerName . ' and method ' . $methodName,
                500,
                $e
            );
        }
    }

    public function getControllerName(): string
    {
        return $this->controllerName;
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }
}
