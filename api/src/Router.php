<?php

declare(strict_types=1);

namespace MyGarden;

use MyGarden\Controllers\ControllerCollection;
use MyGarden\Controllers\GardenController;
use MyGarden\Controllers\PlantController;
use MyGarden\Exceptions\NotFound;
use MyGarden\Request\Request;

class Router
{
    /**
     * @var array<string, array>
     */
    protected array $routes;

    protected Request $request;

    protected PlantController $plantController;

    protected GardenController $gardenController;

    public function __construct(ControllerCollection $controllerCollection, Request $request)
    {
        $this->plantController = $controllerCollection->plantController;

        $this->gardenController = $controllerCollection->gardenController;

        $this->request = $request;

        $this->routes = $this->constructRoutes();

        //TODO this is deeply unsafe and needs to be done properly
        header('Access-Control-Allow-Origin: *');
    }

    /**
     * @throws NotFound
     */
    public function handle(): void
    {
        $matches = [];

        $response = null;

        preg_match_all('/(?<=api\/)([^\/?]+)(\d+){0,25}/', $this->request->uri, $matches);

        $this->routes = $this->routes[$this->request->method];

        $matches = $matches[0];

        foreach ($matches as $match) {
            if (isset($this->routes[$match])) {
                $this->routes = $this->routes[$match];
            }

            if (isset($this->routes['method'])) {
                $this->routes['method']();
                return;
            }
        }

        throw new NotFound();
    }

    /**
     * @return array<string, array>
     */
    private function constructRoutes(): array
    {
        $routes = [];

        $routes['GET']['plant'] = [
            'method' => function (): void {
                $this->plantController->get($this->request);
            }
        ];

        $routes['GET']['plants'] = [
            'method' => function (): void {
                $this->plantController->getAll();
            }
        ];

        $routes['DELETE']['plant'] = [
            'method' => function (): void {
                $this->plantController->delete($this->request);
            }
        ];

        $routes['PUT']['plant'] = [
            'method' => function (): void {
                $this->plantController->update($this->request);
            }
        ];

        $routes['POST']['plant'] = [
            'method' => function (): void {
                $this->plantController->store($this->request);
            }
        ];

        $routes['GET']['garden'] = [
            'method' => function (): void {
                $this->gardenController->get($this->request);
            }
        ];

        $routes['GET']['gardens'] = [
            'method' => function (): void {
                $this->gardenController->getAll();
            }
        ];

        $routes['DELETE']['garden'] = [
            'method' => function (): void {
                $this->gardenController->delete($this->request);
            }
        ];

        $routes['PUT']['garden'] = [
            'method' => function (): void {
                $this->gardenController->update($this->request);
            }
        ];

        $routes['POST']['garden'] = [
            'method' => function (): void {
                $this->gardenController->store($this->request);
            }
        ];

        return $routes;
    }
}
