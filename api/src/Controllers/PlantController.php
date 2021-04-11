<?php

declare(strict_types=1);

namespace MyGarden\Controllers;

use MyGarden\Exceptions\ConstructionFailure;
use MyGarden\Exceptions\MissingParameter;
use MyGarden\Exceptions\NotFound;
use MyGarden\Exceptions\OverMaxChars;
use MyGarden\Exceptions\UnderMinChars;
use MyGarden\Exceptions\WrongTypeParameter;
use MyGarden\Models\Plant;
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
     * @throws NotFound
     * @throws ConstructionFailure
     * @throws \Exception
     * @throws MissingParameter
     * @throws WrongTypeParameter
     */
    public function get(): void
    {
        $this->validator->validateRequestId($this->request);

        $plant = $this->user->getPlant($this->request->params['id']);

        $this->response->setCode(200);

        $this->response->setBodySingleResource($plant);

        $this->view->display($this->response);
    }

    /**
     * @throws NotFound
     * @throws \Exception
     * @throws MissingParameter
     * @throws WrongTypeParameter
     */
    public function delete(): void
    {
        $this->validator->validateRequestId($this->request);

        $this->user->deletePlant($this->request->params['id']);

        $this->response->setCode(204);

        $this->view->display($this->response);
    }

    /**
     * @throws MissingParameter
     * @throws OverMaxChars
     * @throws UnderMinChars
     * @throws WrongTypeParameter
     * @throws \Exception
     */
    public function store(): void
    {
        $this->validator->validateRequestWithoutId($this->request);

        $plant = new Plant(
            null,
            $this->user->getId(),
            $this->request->params['englishName'],
            $this->request->params['latinName'],
            $this->request->params['imageLink']
        );

        $this->user->savePlant($plant);

        $this->response->setCode(201);

        $this->response->setBodySingleResource($plant);

        $this->view->display($this->response);
    }

    /**
     * @throws       \Exception
     * @throws       MissingParameter
     * @throws       WrongTypeParameter
     * @throws       OverMaxChars
     * @throws       UnderMinChars
     * @noinspection ForgottenDebugOutputInspection
     */
    public function update(): void
    {
        $this->validator->validateRequestWithId($this->request);

        $plant = new Plant(
            $this->request->params['id'],
            $this->user->getId(),
            $this->request->params['englishName'],
            $this->request->params['latinName'],
            $this->request->params['imageLink']
        );

        $this->response->setCode(200);

        try {
            $this->user->updatePlant($plant);
        } catch (NotFound $e) {
            error_log("Plant not found in update, creating it instead\n" . $e);

            $this->user->savePlant($plant);

            $this->response->setCode(201);
        }

        $this->response->setBodySingleResource($plant);

        $this->view->display($this->response);
    }
}
