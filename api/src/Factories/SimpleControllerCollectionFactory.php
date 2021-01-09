<?php

declare(strict_types = 1);

namespace MyGarden\Factories;

use MyGarden\Controllers\ControllerCollection;
use MyGarden\Controllers\GardenController;
use MyGarden\Controllers\PlantController;
use MyGarden\Exceptions\OverMaxChars;
use MyGarden\Exceptions\UnderMinChars;
use MyGarden\Repositories\RepositoryCollection;
use MyGarden\Request\Request;
use MyGarden\Responses\JsonMappedResponse;
use MyGarden\Responses\ResponseInterface;
use MyGarden\Views\HtmlView;
use MyGarden\Views\JsonView;
use MyGarden\Views\ViewInterface;

class SimpleControllerCollectionFactory
{
    protected ViewInterface $view;

    protected ResponseInterface $response;

    protected RepositoryCollection $repositoryCollection;

    public function __construct(RepositoryCollection $repositoryCollection)
    {
        $this->repositoryCollection = $repositoryCollection;

        //default view
        $this->view = new JsonView();

        //default response
        $this->response = new JsonMappedResponse();
    }

    /**
     * @param Request $request
     * @return ControllerCollection
     * @throws OverMaxChars
     * @throws UnderMinChars
     * @throws \Exception
     */
    public function create(Request $request): ControllerCollection
    {
        $acceptHeader = $request->acceptHeader;

        if (
            $acceptHeader === [] ||
            !in_array('application/json', $acceptHeader)
        ){
            $this->view = new HtmlView();

            $this->response = new JsonMappedResponse();
        }

        $plantController = new PlantController($this->repositoryCollection, $this->response, $this->view);

        $gardenController = new GardenController($this->repositoryCollection, $this->response, $this->view);

        return new ControllerCollection($plantController, $gardenController);
    }
}