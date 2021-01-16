<?php

declare(strict_types = 1);

namespace MyGarden;

use MyGarden\Controllers\ControllerCollection;
use MyGarden\Controllers\GardenController;
use MyGarden\Controllers\PlantController;
use MyGarden\Request\Request;

class Routes
{
    /**
     * @var array<string, array>
     */
    protected array $routes;

    protected PlantController $plantController;

    protected GardenController $gardenController;

    protected Request $request;

    public function __construct(ControllerCollection $controllerCollection, Request $request)
    {
        $this->plantController = $controllerCollection->plantController;

        $this->gardenController = $controllerCollection->gardenController;

        $this->routes = $this->createRoutes();

        $this->request = $request;
    }

    /**
     * @return array<string, array>
     */
    public function get(): array
    {
        return $this->routes;
    }

    /**
     * @return array<string, array>
     */
    protected function createRoutes(): array
    {
        $routes = [];

        $routes['GET']['plant'] = [
            'method' => function(): void
            {
                $this->plantController->get($this->request);
            }
        ];

        $routes['GET']['plants'] = [
            'method' => function(): void
            {
                $this->plantController->getAll();
            }
        ];

        $routes['DELETE']['plant'] = [
            'method' => function(): void
            {
                $this->plantController->delete($this->request);
            }
        ];

        $routes['PUT']['plant'] = [
            'method' => function(): void
            {
                $this->plantController->update($this->request);
            }
        ];

        $routes['POST']['plant'] = [
            'method' => function(): void
            {
                $this->plantController->store($this->request);
            }
        ];

        $routes['GET']['garden'] = [
            'method' => function(): void
            {
                $this->gardenController->get($this->request);
            }
        ];

        $routes['GET']['gardens'] = [
            'method' => function(): void
            {
                $this->gardenController->getAll();
            }
        ];

        $routes['DELETE']['garden'] = [
            'method' => function(): void
            {
                $this->gardenController->delete($this->request);
            }
        ];

        $routes['PUT']['garden'] = [
            'method' => function(): void
            {
                $this->gardenController->update($this->request);
            }
        ];

        $routes['POST']['garden'] = [
            'method' => function(): void
            {
                $this->gardenController->store($this->request);
            }
        ];

        return $routes;
    }
}
