<?php

declare(strict_types = 1);

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
     * @param Request $request
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

        preg_match_all('/(?<=api\/)([^\/?]+)(\d+)*/', $request->uri, $matches);

        $this->routes = $this->routes[$request->method];

        $matches = $matches[0];

        for($i = 0; $i < count($matches); $i++){

            if (isset($this->routes[$matches[$i]])) {
                $this->routes = $this->routes[$matches[$i]];
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
            'method' => function(){
                $this->plantController->get($this->request);
            }
        ];

        $this->routes['GET']['plants'] = [
            'method' => function(){
                $this->plantController->getAll();
            }
        ];

        $this->routes['DELETE']['plant'] = [
            'method' => function(){
                $this->plantController->delete($this->request);
            }
        ];

        $this->routes['PUT']['plant'] = [
            'method' => function(){
                $this->plantController->update($this->request);
            }
        ];

        $this->routes['POST']['plant'] = [
            'method' => function(){
                $this->plantController->store($this->request);
            }
        ];

        $this->routes['GET']['garden'] = [
            'method' => function(){
                $this->gardenController->get($this->request);
            }
        ];

        $this->routes['GET']['gardens'] = [
            'method' => function(){
                $this->gardenController->getAll();
            }
        ];

        $this->routes['DELETE']['garden'] = [
            'method' => function(){
                $this->gardenController->delete($this->request);
            }
        ];

        $this->routes['PUT']['garden'] = [
            'method' => function(){
                $this->gardenController->update($this->request);
            }
        ];

        $this->routes['POST']['garden'] = [
            'method' => function(){
                $this->gardenController->store($this->request);
            }
        ];
    }
}