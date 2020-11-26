<?php

declare(strict_types = 1);

namespace MyGarden;

use MyGarden\Controllers\PlantController;
use MyGarden\Controllers\UserController;
use MyGarden\Database\DatabaseConnection;
use MyGarden\Repositories\RepositoryCollection;
use MyGarden\Request\Request;

class Router
{
    protected array $routes;

    public function __construct()
    {
        $this->databaseConnection = new DatabaseConnection();

        $this->repositoryCollection = new RepositoryCollection();

        $this->repositoryCollection->databaseConnection = $this->databaseConnection;

        $this->userController = new UserController($this->repositoryCollection);

        $this->user = $this->userController->getUserFromEmailAndPassword('dan@email.com', 'password');

        $this->plantController = new PlantController($this->repositoryCollection, $this->user);
    }

    public function handle(Request $request)
    {
        header('Content-Type: application/json');

        //TODO this is deeply unsafe and needs to be done properly
        header("Access-Control-Allow-Origin: *");

        $matches = [];

        $response = null;

        $routes['GET']['plant'] = [
            'method' => function() use ($request){
                return $this->plantController->get($request);
            },
            'code' => 200
        ];

        $routes['GET']['plants'] = [
            'method' => function() use ($request){
                return $this->plantController->getAll()->getItems();
            },
            'code' => 200
        ];

        $routes['DELETE']['plant'] = [
            'method' => function() use ($request){
                $this->plantController->delete($request);
                return null;
            },
            'code' => 204
        ];

        $routes['PUT']['plant'] = [
            'method' => function() use ($request){
                return $this->plantController->update($request);
            },
            'code' => 200
        ];

        $routes['POST']['plant'] = [
            'method' => function() use ($request){
                return $this->plantController->store($request);
            },
            'code' => 201
        ];

        preg_match_all('/(?<=api\/)([^\/?]+)(\d+)*/', $request->uri, $matches);

        $routes = $routes[$request->method];

        $matches = $matches[0];

        $found = false;

        for($i = 0; $i < count($matches); $i++){

            if (isset($routes[$matches[$i]])) {
                $routes = $routes[$matches[$i]];
            }

            if (isset($routes['method'])) {
                $response = $routes['method']();
                http_response_code($routes['code']);
                $found = true;
                break;
            }
        }

        if (!$found){
            $response = 'Unrecognised request';
            http_response_code(404);
        }

        echo json_encode($response);
    }


}