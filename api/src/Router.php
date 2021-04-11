<?php

declare(strict_types=1);

namespace MyGarden;

use MyGarden\Controllers\GardenController;
use MyGarden\Controllers\PlantController;
use MyGarden\Exceptions\NotFound;
use MyGarden\Factories\SimpleControllerFactory;
use MyGarden\Request\Request;

class Router
{
    /**
     * @var array<string, array>
     */
    protected array $routes;

    protected PlantController $plantController;

    protected GardenController $gardenController;

    private SimpleControllerFactory $controllerFactory;

    public function __construct(SimpleControllerFactory $controllerFactory)
    {
        $this->controllerFactory = $controllerFactory;
    }

    /**
     * @param Request $request
     * @throws NotFound
     * @throws \Exception
     */
    public function handle(Request $request): void
    {
        $this->populateRoutes();

        //TODO this is deeply unsafe and needs to be done properly
        header('Access-Control-Allow-Origin: *');

        $matches = [];

        preg_match_all('/([^?\/]+)/', $request->uri, $matches);

        $routes = $this->routes[$request->method];

        $matches = $matches[0];

        foreach ($matches as $match) {
            if ($match == "api") {
                continue;
            }

            if (!isset($routes[$match])) {
                throw new NotFound();
            }

            if (true !== ($routes[$match] instanceof Route)) {
                $routes = $routes[$match];
                continue;
            }

            $route = $routes[$match];

            $controller = $this->controllerFactory->create($route->controllerName);

            $method = $route->method;

            $controller->$method();

            return;
        }

        throw new NotFound();
    }

    /**
     * @throws \Exception
     */
    protected function populateRoutes(): void
    {
        $this->routes['GET']['plants'] = new Route(
            PlantController::class,
            'getAll'
        );

        $this->routes['GET']['plant'] = new Route(
            PlantController::class,
            'get'
        );

        $this->routes['DELETE']['plant'] = new Route(
            PlantController::class,
            'delete'
        );

        $this->routes['POST']['plant'] = new Route(
            PlantController::class,
            'store'
        );

        $this->routes['PUT']['plant'] = new Route(
            PlantController::class,
            'update'
        );

        $this->routes['GET']['gardens'] = new Route(
            GardenController::class,
            'getAll'
        );

        $this->routes['GET']['garden'] = new Route(
            GardenController::class,
            'get'
        );

        $this->routes['DELETE']['garden'] = new Route(
            GardenController::class,
            'delete'
        );

        $this->routes['POST']['garden'] = new Route(
            GardenController::class,
            'store'
        );

        $this->routes['PUT']['garden'] = new Route(
            GardenController::class,
            'update'
        );
    }
}
