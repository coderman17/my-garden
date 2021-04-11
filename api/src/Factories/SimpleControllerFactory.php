<?php

declare(strict_types=1);

namespace MyGarden\Factories;

use MyGarden\Controllers\Controller;
use MyGarden\Request\Request;
use MyGarden\Responses\JsonMappedResponse;
use MyGarden\Interfaces\ResponseInterface;
use MyGarden\Views\HtmlView;
use MyGarden\Views\JsonView;
use MyGarden\Interfaces\ViewInterface;

class SimpleControllerFactory
{
    protected ViewInterface $view;

    protected ResponseInterface $response;

    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        //default view
        $this->view = new JsonView();

        //default response
        $this->response = new JsonMappedResponse();

        $acceptHeader = $request->acceptHeader;

        if (
            $acceptHeader === []
            || !in_array('application/json', $acceptHeader, true)
        ) {
            $this->view = new HtmlView();
        }
    }

    public function create(string $controllerName): Controller
    {
        return new $controllerName($this->request, $this->response, $this->view);
    }
}
