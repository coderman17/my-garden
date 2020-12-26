<?php

declare(strict_types = 1);

namespace MyGarden;

use MyGarden\Controllers\PlantController;
use MyGarden\Repositories\RepositoryCollection;
use MyGarden\Request\Request;
use MyGarden\Views\JsonView;

class Router
{
    /**
     * @var array<string, array>
     */
    protected array $routes;

    protected RepositoryCollection $repositoryCollection;

    protected JsonView $view;

    protected PlantController $plantController;

    /**
     * @param RepositoryCollection $repositoryCollection
     * @throws Exceptions\OutOfRangeInt
     * @throws Exceptions\OverMaxChars
     * @throws Exceptions\UnderMinChars
     */
    public function __construct(RepositoryCollection $repositoryCollection)
    {
        $this->repositoryCollection = $repositoryCollection;

        $this->view = new JsonView();

        $this->plantController = new PlantController($this->repositoryCollection, $this->view);
    }

    public function handle(Request $request): void
    {
        //TODO this is deeply unsafe and needs to be done properly
        header('Access-Control-Allow-Origin: *');

        $matches = [];

        $response = null;

        $this->routes['GET']['plant'] = [
            'method' => function() use ($request){
                $this->plantController->get($request);
            }
        ];

        $this->routes['GET']['plants'] = [
            'method' => function(){
                $this->plantController->getAll();
            }
        ];

        $this->routes['DELETE']['plant'] = [
            'method' => function() use ($request){
                $this->plantController->delete($request);
            }
        ];

        $this->routes['PUT']['plant'] = [
            'method' => function() use ($request){
                $this->plantController->update($request);
            }
        ];

        $this->routes['POST']['plant'] = [
            'method' => function() use ($request){
                $this->plantController->store($request);
            }
        ];

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

        //TODO make an exception for 404
        $response = 'Unrecognised request';
        http_response_code(404);

        echo json_encode(['error' => $response]);
    }
}