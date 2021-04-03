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

    public function __construct(ControllerCollection $controllerCollection)
    {
        $this->plantController = $controllerCollection->plantController;

        $this->gardenController = $controllerCollection->gardenController;
    }

    /**
     * @param  Request $request
     * @throws NotFound
     */
    public function handle(Request $request): void
    {
        $this->request = $request;

        $this->populateRoutes();

        //TODO this is deeply unsafe and needs to be done properly
        header('Access-Control-Allow-Origin: *');

        $matches = [];

        $response = null;

        preg_match_all('/(?<=api\/)([^\/?]+)(\d+){0,25}/', $request->uri, $matches);

        $this->routes = $this->routes[$request->method];

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

    protected function populateRoutes(): void
    {
        $this->routes['GET']['plant'] = [
            'method' => function (): void {
                $this->plantController->get($this->request);
            }
        ];

        $this->routes['GET']['plants'] = [
            'method' => function (): void {
                $this->plantController->getAll();
            }
        ];

        $this->routes['DELETE']['plant'] = [
            'method' => function (): void {
                $this->plantController->delete($this->request);
            }
        ];

        $this->routes['PUT']['plant'] = [
            'method' => function (): void {
                $this->plantController->update($this->request);
            }
        ];

        $this->routes['POST']['plant'] = [
            'method' => function (): void {
                $this->plantController->store($this->request);
            }
        ];

        $this->routes['GET']['garden'] = [
            'method' => function (): void {
                $this->gardenController->get($this->request);
            }
        ];

        $this->routes['GET']['gardens'] = [
            'method' => function (): void {
                $this->gardenController->getAll();
            }
        ];

        $this->routes['DELETE']['garden'] = [
            'method' => function (): void {
                $this->gardenController->delete($this->request);
            }
        ];

        $this->routes['PUT']['garden'] = [
            'method' => function (): void {
                $this->gardenController->update($this->request);
            }
        ];

        $this->routes['POST']['garden'] = [
            'method' => function (): void {
                $this->gardenController->store($this->request);
            }
        ];
    }
}
