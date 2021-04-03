<?php

declare(strict_types=1);

namespace MyGarden\Factories;

use MyGarden\Controllers\ControllerCollection;
use MyGarden\Controllers\GardenController;
use MyGarden\Controllers\PlantController;
use MyGarden\Request\Request;
use MyGarden\Responses\JsonMappedResponse;
use MyGarden\Interfaces\ResponseInterface;
use MyGarden\Views\HtmlView;
use MyGarden\Views\JsonView;
use MyGarden\Interfaces\ViewInterface;

class SimpleControllerCollectionFactory
{
    protected ViewInterface $view;

    protected ResponseInterface $response;

    public function __construct()
    {
        //default view
        $this->view = new JsonView();

        //default response
        $this->response = new JsonMappedResponse();
    }

    /**
     * @param  Request $request
     * @return ControllerCollection
     * @throws \Exception
     */
    public function create(Request $request): ControllerCollection
    {
        $acceptHeader = $request->acceptHeader;

        if (
            $acceptHeader === []
            || !in_array('application/json', $acceptHeader, true)
        ) {
            $this->view = new HtmlView();
        }

        $plantController = new PlantController($this->response, $this->view);

        $gardenController = new GardenController($this->response, $this->view);

        return new ControllerCollection($plantController, $gardenController);
    }
}
