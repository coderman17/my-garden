<?php

declare(strict_types=1);

namespace MyGarden;

class Route
{
    public string $controllerName;

    public string $method;

    /**
     * @throws \Exception
     */
    public function __construct(string $controller, string $method)
    {
        $this->validate($controller, $method);

        $this->controllerName = $controller;

        $this->method = $method;
    }

    /**
     * @param string $controller
     * @param string $method
     * @throws \Exception
     */
    private function validate(string $controller, string $method): void
    {
        try {
            //TODO check performance of reflection
            new \ReflectionMethod($controller, $method);
        } catch (\Throwable $e) {
            throw new \Exception('A Route was invalid', 500, $e);
        }
    }
}
