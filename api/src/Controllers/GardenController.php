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
use MyGarden\Request\Request;
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
     * @param  Request $request
     * @throws MissingParameter
     * @throws WrongTypeParameter
     * @throws NotFound
     * @throws \Exception
     * @throws ConstructionFailure
     */
    public function get(Request $request): void
    {
        $this->validator->validateRequestId($request);

        $garden = $this->user->getGarden($request->params['id']);

        $this->response->setCode(200);

        $this->response->setBodySingleResource($garden);

        $this->view->display($this->response);
    }


    /**
     * @param  Request $request
     * @throws MissingParameter
     * @throws NotFound
     * @throws WrongTypeParameter
     * @throws \Exception
     */
    public function delete(Request $request): void
    {
        $this->validator->validateRequestId($request);

        $this->user->deleteGarden($request->params['id']);

        $this->response->setCode(204);

        $this->view->display($this->response);
    }

    /**
     * @param  Request $request
     * @throws MissingParameter
     * @throws OutOfRangeInt
     * @throws OverMaxChars
     * @throws UnderMinChars
     * @throws WrongTypeParameter
     * @throws \Exception
     * @throws NotFound
     * @throws ConstructionFailure
     */
    public function store(Request $request): void
    {
        $this->validator->validateRequestWithoutId($request);

        $garden = new Garden(
            null,
            $this->user->getId(),
            $request->params['name'],
            $request->params['dimensionX'],
            $request->params['dimensionY']
        );

        $this->populateGardenWithLocations($garden, $request);

        $this->user->saveGarden($garden);

        $this->response->setCode(201);

        $this->response->setBodySingleResource($garden);

        $this->view->display($this->response);
    }

    /**
     * @param  Garden  $garden
     * @param  Request $request
     * @throws ConstructionFailure
     * @throws NotFound
     * @throws OutOfRangeInt
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    protected function populateGardenWithLocations(Garden $garden, Request $request): void
    {
        $plantLocations = [];

        foreach ($request->params['plantLocations'] as $plantLocation) {
            $plantLocations[] = new PlantLocation(
                $plantLocation['id'],
                $plantLocation['coordinateX'],
                $plantLocation['coordinateY']
            );
        }

        $this->user->setPlantLocations($garden, $plantLocations);
    }

    /**
     * @param        Request $request
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
    public function update(Request $request): void
    {
        $this->validator->validateRequestWithId($request);

        $garden = new Garden(
            $request->params['id'],
            $this->user->getId(),
            $request->params['name'],
            $request->params['dimensionX'],
            $request->params['dimensionY']
        );

        $this->populateGardenWithLocations($garden, $request);

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
