<?php

declare(strict_types = 1);

namespace MyGarden;

use MyGarden\Database\DatabaseConnection;
use MyGarden\Repositories\RepositoryCollection;
use MyGarden\Request\Request;

include '.env.php';

class App
{
    protected DatabaseConnection $databaseConnection;

    protected RepositoryCollection $repositoryCollection;

    protected Request $request;

    protected Router $router;

    public function __construct()
    {
        $this->databaseConnection = new DatabaseConnection();

        $this->repositoryCollection = new RepositoryCollection();

        $this->repositoryCollection->databaseConnection = $this->databaseConnection;

        $this->request = new Request();

        $this->router = new Router($this->repositoryCollection);
    }

    public function run()
    {
//        error_log(date("Y-m-d\TH:i:s") . substr((string)microtime(), 1, 8));

        $this->router->handle($this->request);

//        error_log(date("Y-m-d\TH:i:s") . substr((string)microtime(), 1, 8));
    }
}