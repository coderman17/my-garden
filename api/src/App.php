<?php

declare(strict_types = 1);

namespace MyGarden;

use MyGarden\Controllers\ControllerCollection;
use MyGarden\Database\DatabaseConnection;
use MyGarden\Factories\ControllerFactory;
use MyGarden\Repositories\RepositoryCollection;
use MyGarden\Request\Request;

class App
{
    protected DatabaseConnection $databaseConnection;

    protected RepositoryCollection $repositoryCollection;

    protected Request $request;

    protected Router $router;

    protected ControllerFactory $controllerFactory;

    protected ControllerCollection $controllerCollection;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->databaseConnection = new DatabaseConnection();

        $this->repositoryCollection = new RepositoryCollection();

        $this->repositoryCollection->databaseConnection = $this->databaseConnection;

        $this->request = new Request();

        $this->controllerFactory = new ControllerFactory($this->repositoryCollection);

        $this->controllerCollection = $this->controllerFactory->generateControllers($this->request);

        $this->router = new Router($this->controllerCollection);
    }

    public function run(): void
    {
//        var_dump($this->request->acceptHeader);
//        exit();
//        error_log(date("Y-m-d\TH:i:s") . substr((string)microtime(), 1, 8));

        $this->router->handle($this->request);

//        error_log(date("Y-m-d\TH:i:s") . substr((string)microtime(), 1, 8));
    }
}
