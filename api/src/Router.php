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

        $matches = [];

        $response = null;

        //works with: http://localhost/api/plant?id=17
        $routes['GET']['api']['plant'] = [
            'method' => function() use ($request){
//          This can be moved to controller, just pass request to controller and let it validate the params:
//          $id = $this->validateInteger($matches[2]);

//          only using GET here for proof of concept, GET should be saved away on Request and router shouldn't know
                return $this->plantController->get(intval($_GET['id']));
            },
            'code' => 200
        ];

        $routes['GET']['api']['plants'] = [
            'method' => function() use ($request){
                return $this->plantController->getAll()->getItems();
            },
            'code' => 200
        ];

        $routes['DELETE']['api']['plant'] = [
            'method' => function() use ($request, &$matches){
                $this->plantController->delete(intval($matches[2]));
                return null;
            },
            'code' => 204
        ];

        $routes['PUT']['api']['plant'] = [
            'method' => function() use ($request, &$matches){
                return $this->plantController->update(intval($matches[2]), $request->body['englishName'], $request->body['latinName']);
            },
            'code' => 200
        ];

        $routes['POST']['api']['plant'] = [
            'method' => function() use ($request){
                return $this->plantController->store($request->body['englishName'], $request->body['latinName']);
            },
            'code' => 201
        ];

        preg_match_all('/(?<=\/)([^\/?]+)(\d+)*/', $request->uri, $matches);

        $routes = $routes[$request->requestMethod];

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

    protected function validateInteger(string $string): int
    {
        if (preg_match('/[^0-9]+/', $string)){
            throw new \Exception('Could not determine an integer in the uri');
        }

        return intval($string);
    }
}