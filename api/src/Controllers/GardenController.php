<?php

declare(strict_types = 1);

namespace MyGarden\Controllers;

use MyGarden\Exceptions\MissingParameter;
use MyGarden\Exceptions\NotFound;
use MyGarden\Exceptions\OutOfRangeInt;
use MyGarden\Exceptions\OverMaxChars;
use MyGarden\Exceptions\UnderMinChars;
use MyGarden\Exceptions\WrongTypeParameter;
use MyGarden\Models\Garden;
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
     * @throws OutOfRangeInt
     * @throws OverMaxChars
     * @throws UnderMinChars
     * @throws \Exception
     */
    public function getAll(): void
    {
        $gardenArray = $this->repositoryCollection->gardenRepository->getUserGardens($this->user->getId());

        $this->response->setCode(200);

        $this->response->setBodyCollectionResource($gardenArray);

        $this->view->display($this->response);
    }

    /**
     * @param Request $request
     * @throws MissingParameter
     * @throws OutOfRangeInt
     * @throws OverMaxChars
     * @throws UnderMinChars
     * @throws WrongTypeParameter
     * @throws NotFound
     * @throws \Exception
     */
    public function get(Request $request): void
    {
        $this->validator->validateRequestId($request);

        $garden = $this->repositoryCollection->gardenRepository->getUserGarden(
            $this->user->getId(),
            $request->params['id']
        );

        $this->response->setCode(200);

        $this->response->setBodySingleResource($garden);

        $this->view->display($this->response);
    }


    /**
     * @param Request $request
     * @throws MissingParameter
     * @throws NotFound
     * @throws WrongTypeParameter
     * @throws \Exception
     */
    public function delete(Request $request): void
    {
        $this->validator->validateRequestId($request);

        $this->repositoryCollection->gardenRepository->deleteUserGarden(
            $this->user->getId(),
            $request->params['id']
        );

        $this->response->setCode(204);

        $this->view->display($this->response);
    }

    /**
     * @param Request $request
     * @throws MissingParameter
     * @throws OutOfRangeInt
     * @throws OverMaxChars
     * @throws UnderMinChars
     * @throws WrongTypeParameter
     * @throws \Exception
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

        foreach($request->params['plantLocations'] as $plantLocation){
            //TODO perhaps this should be one call to get them in bulk or get all user plants then select relevant ones
            $plant = $this->repositoryCollection->plantRepository->getUserPlant(
                $this->user->getId(),
                $plantLocation['id']
            );

            $garden->setPlantLocation(
                $plant,
                $plantLocation['coordinateX'],
                $plantLocation['coordinateY']
            );
        }

        $this->repositoryCollection->gardenRepository->saveUserGarden($garden);

        $this->response->setCode(201);

        $this->response->setBodySingleResource($garden);

        $this->view->display($this->response);
    }

    /**
     * @param Request $request
     * @throws MissingParameter
     * @throws OutOfRangeInt
     * @throws OverMaxChars
     * @throws UnderMinChars
     * @throws WrongTypeParameter
     * @throws \Exception
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

        foreach($request->params['plantLocations'] as $plantLocation){
            //TODO perhaps this should be one call to get them in bulk or get all user plants then select relevant ones
            $plant = $this->repositoryCollection->plantRepository->getUserPlant(
                $this->user->getId(),
                $plantLocation['id']
            );

            $garden->setPlantLocation(
                $plant,
                $plantLocation['coordinateX'],
                $plantLocation['coordinateY']
            );
        }

        $this->response->setCode(200);

        try {
            $this->repositoryCollection->gardenRepository->updateUserGarden($garden);
        } catch (NotFound $e) {
            $this->repositoryCollection->gardenRepository->saveUserGarden($garden);

            $this->response->setCode(201);
        }

        $this->response->setBodySingleResource($garden);

        $this->view->display($this->response);
    }
}