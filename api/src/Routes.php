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

        $this->populateRoutes();

        $this->request = $request;
    }

    /**
     * @return array<string, array>
     */
    public function get(): array
    {
        return $this->routes;
    }

    protected function populateRoutes(): void
    {
        $this->routes['GET']['plant'] = [
            'method' => function(): void
            {
                $this->plantController->get($this->request);
            }
        ];

        $this->routes['GET']['plants'] = [
            'method' => function(): void
            {
                $this->plantController->getAll();
            }
        ];

        $this->routes['DELETE']['plant'] = [
            'method' => function(): void
            {
                $this->plantController->delete($this->request);
            }
        ];

        $this->routes['PUT']['plant'] = [
            'method' => function(): void
            {
                $this->plantController->update($this->request);
            }
        ];

        $this->routes['POST']['plant'] = [
            'method' => function(): void
            {
                $this->plantController->store($this->request);
            }
        ];

        $this->routes['GET']['garden'] = [
            'method' => function(): void
            {
                $this->gardenController->get($this->request);
            }
        ];

        $this->routes['GET']['gardens'] = [
            'method' => function(): void
            {
                $this->gardenController->getAll();
            }
        ];

        $this->routes['DELETE']['garden'] = [
            'method' => function(): void
            {
                $this->gardenController->delete($this->request);
            }
        ];

        $this->routes['PUT']['garden'] = [
            'method' => function(): void
            {
                $this->gardenController->update($this->request);
            }
        ];

        $this->routes['POST']['garden'] = [
            'method' => function(): void
            {
                $this->gardenController->store($this->request);
            }
        ];
    }
}
