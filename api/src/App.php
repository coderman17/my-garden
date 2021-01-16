<?php

declare(strict_types = 1);

namespace MyGarden;

use MyGarden\Controllers\ControllerCollection;
use MyGarden\Factories\SimpleControllerCollectionFactory;
use MyGarden\Request\Request;

class App
{
    protected Request $request;

    protected Router $router;

    protected SimpleControllerCollectionFactory $controllerFactory;

    protected ControllerCollection $controllerCollection;

    protected Routes $routes;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->request = new Request();

        $this->controllerFactory = new SimpleControllerCollectionFactory();

        $this->controllerCollection = $this->controllerFactory->create($this->request);

        $this->routes = new Routes($this->controllerCollection, $this->request);

        $this->router = new Router($this->routes, $this->request);
    }

    /**
     * @throws Exceptions\NotFound
     */
    public function run(): void
    {
//        error_log(date("Y-m-d\TH:i:s") . substr((string)microtime(), 1, 8));

        $this->router->handleRequest();

//        error_log(date("Y-m-d\TH:i:s") . substr((string)microtime(), 1, 8));
    }
}
