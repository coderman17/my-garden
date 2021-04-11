<?php

declare(strict_types=1);

namespace MyGarden\Controllers;

use MyGarden\Exceptions\ConstructionFailure;
use MyGarden\Exceptions\MissingParameter;
use MyGarden\Exceptions\NotFound;
use MyGarden\Exceptions\OutOfRangeInt;
use MyGarden\Exceptions\OverMaxChars;
use MyGarden\Exceptions\UnderMinChars;
use MyGarden\Exceptions\WrongTypeParameter;
use MyGarden\Models\Garden;
use MyGarden\Models\PlantLocation;
use MyGarden\Validators\GardenValidator;
use MyGarden\Validators\Validator;

class GardenController extends Controller
{
    protected function getValidator(): Validator
    {
        return new GardenValidator();
    }

    /**
     * @throws \Exception
     * @throws \InvalidArgumentException
     * @throws ConstructionFailure
     */
    public function getAll(): void
    {
        $gardenArray = $this->user->getGardens();

        $this->response->setCode(200);

        $this->response->setBodyCollectionResource($gardenArray->getItems());

        $this->view->display($this->response);
    }

    /**
     * @throws MissingParameter
     * @throws WrongTypeParameter
     * @throws NotFound
     * @throws \Exception
     * @throws ConstructionFailure
     */
    public function get(): void
    {
        $this->validator->validateRequestId($this->request);

        $garden = $this->user->getGarden($this->request->params['id']);

        $this->response->setCode(200);

        $this->response->setBodySingleResource($garden);

        $this->view->display($this->response);
    }


    /**
     * @throws MissingParameter
     * @throws NotFound
     * @throws WrongTypeParameter
     * @throws \Exception
     */
    public function delete(): void
    {
        $this->validator->validateRequestId($this->request);

        $this->user->deleteGarden($this->request->params['id']);

        $this->response->setCode(204);

        $this->view->display($this->response);
    }

    /**
     * @throws MissingParameter
     * @throws OutOfRangeInt
     * @throws OverMaxChars
     * @throws UnderMinChars
     * @throws WrongTypeParameter
     * @throws \Exception
     * @throws NotFound
     * @throws ConstructionFailure
     */
    public function store(): void
    {
        $this->validator->validateRequestWithoutId($this->request);

        $garden = new Garden(
            null,
            $this->user->getId(),
            $this->request->params['name'],
            $this->request->params['dimensionX'],
            $this->request->params['dimensionY']
        );

        $this->populateGardenWithLocations($garden);

        $this->user->saveGarden($garden);

        $this->response->setCode(201);

        $this->response->setBodySingleResource($garden);

        $this->view->display($this->response);
    }

    /**
     * @param  Garden  $garden
     * @throws ConstructionFailure
     * @throws NotFound
     * @throws OutOfRangeInt
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    protected function populateGardenWithLocations(Garden $garden): void
    {
        $plantLocations = [];

        foreach ($this->request->params['plantLocations'] as $plantLocation) {
            $plantLocations[] = new PlantLocation(
                $plantLocation['id'],
                $plantLocation['coordinateX'],
                $plantLocation['coordinateY']
            );
        }

        $this->user->setPlantLocations($garden, $plantLocations);
    }

    /**
     * @throws       MissingParameter
     * @throws       OutOfRangeInt
     * @throws       OverMaxChars
     * @throws       UnderMinChars
     * @throws       WrongTypeParameter
     * @throws       \Exception
     * @throws       ConstructionFailure
     * @throws       NotFound
     * @noinspection ForgottenDebugOutputInspection
     */
    public function update(): void
    {
        $this->validator->validateRequestWithId($this->request);

        $garden = new Garden(
            $this->request->params['id'],
            $this->user->getId(),
            $this->request->params['name'],
            $this->request->params['dimensionX'],
            $this->request->params['dimensionY']
        );

        $this->populateGardenWithLocations($garden);

        $this->response->setCode(200);

        try {
            $this->user->updateGarden($garden);
        } catch (NotFound $e) {
            error_log("Garden not found in update, creating it instead\n" . $e);

            $this->user->saveGarden($garden);

            $this->response->setCode(201);
        }

        $this->response->setBodySingleResource($garden);

        $this->view->display($this->response);
    }
}
