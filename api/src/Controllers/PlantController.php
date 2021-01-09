<?php

declare(strict_types = 1);

namespace MyGarden\Controllers;

use MyGarden\Exceptions\ConstructionFailure;
use MyGarden\Exceptions\MissingParameter;
use MyGarden\Exceptions\NotFound;
use MyGarden\Exceptions\OverMaxChars;
use MyGarden\Exceptions\UnderMinChars;
use MyGarden\Exceptions\WrongTypeParameter;
use MyGarden\Models\Plant;
use MyGarden\Request\Request;
use MyGarden\Validators\PlantValidator;
use MyGarden\Validators\Validator;

class PlantController extends Controller
{
    protected function getValidator(): Validator
    {
        return new PlantValidator();
    }

    /**
     * @throws \Exception
     * @throws ConstructionFailure
     * @throws \InvalidArgumentException
     */
    public function getAll(): void
    {
        $plantArray = $this->user->getPlants();

        $this->response->setCode(200);

        $this->response->setBodyCollectionResource($plantArray->getItems());

        $this->view->display($this->response);
    }

    /**
     * @param Request $request
     * @throws NotFound
     * @throws ConstructionFailure
     * @throws \Exception
     * @throws MissingParameter
     * @throws WrongTypeParameter
     */
    public function get(Request $request): void
    {
        $this->validator->validateRequestId($request);

        $plant = $this->user->getPlant($request->params['id']);

        $this->response->setCode(200);

        $this->response->setBodySingleResource($plant);

        $this->view->display($this->response);
    }

    /**
     * @param Request $request
     * @throws NotFound
     * @throws \Exception
     * @throws MissingParameter
     * @throws WrongTypeParameter
     */
    public function delete(Request $request): void
    {
        $this->validator->validateRequestId($request);

        $this->user->deletePlant($request->params['id']);

        $this->response->setCode(204);

        $this->view->display($this->response);
    }

    /**
     * @param Request $request
     * @throws MissingParameter
     * @throws OverMaxChars
     * @throws UnderMinChars
     * @throws WrongTypeParameter
     * @throws \Exception
     */
    public function store(Request $request): void
    {
        $this->validator->validateRequestWithoutId($request);

        $plant = new Plant(
            null,
            $this->user->getId(),
            $request->params['englishName'],
            $request->params['latinName'],
            $request->params['imageLink']
        );

        $this->user->savePlant($plant);

        $this->response->setCode(201);

        $this->response->setBodySingleResource($plant);

        $this->view->display($this->response);
    }

    /**
     * @param Request $request
     * @throws \Exception
     * @throws MissingParameter
     * @throws WrongTypeParameter
     * @throws OverMaxChars
     * @throws UnderMinChars
     */
    public function update(Request $request): void
    {
        $this->validator->validateRequestWithId($request);

        $plant = new Plant(
            $request->params['id'],
            $this->user->getId(),
            $request->params['englishName'],
            $request->params['latinName'],
            $request->params['imageLink']
        );

        $this->response->setCode(200);

        try {
            $this->user->updatePlant($plant);
        } catch (NotFound $e) {
            $this->user->savePlant($plant);

            $this->response->setCode(201);
        }

        $this->response->setBodySingleResource($plant);

        $this->view->display($this->response);
    }
}