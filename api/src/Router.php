<?php

declare(strict_types = 1);

namespace MyGarden;

use MyGarden\Exceptions\NotFound;
use MyGarden\Request\Request;

class Router
{
    /**
     * @var array<string, array>
     */
    protected array $routes;

    protected Request $request;

    public function __construct(Routes $routes, Request $request)
    {
        $this->routes = $routes->get();

        $this->request = $request;
    }

    /**
     * @throws NotFound
     */
    public function handleRequest(): void
    {
        //TODO this is deeply unsafe and needs to be done properly
        header('Access-Control-Allow-Origin: *');

        $matches = [];

        $response = null;

        preg_match_all('/(?<=api\/)([^\/?]+)(\d+)*/', $this->request->uri, $matches);

        $this->routes = $this->routes[$this->request->method];

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
}
