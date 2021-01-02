<?php

declare(strict_types = 1);

namespace MyGarden\Controllers;

use MyGarden\Exceptions\MissingParameter;
use MyGarden\Exceptions\NotFound;
use MyGarden\Exceptions\OutOfRangeInt;
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
     */
    public function getAll(): void
    {
        $plantArray = $this->repositoryCollection->plantRepository->getUserPlants($this->user->getId());

        $this->response->setCode(200);

        $this->response->setBodyCollectionResource($plantArray);

        $this->view->display($this->response);
    }

    /**
     * @param Request $request
     * @throws NotFound
     * @throws OutOfRangeInt
     * @throws OverMaxChars
     * @throws UnderMinChars
     * @throws \Exception
     */
    public function get(Request $request): void
    {
        $this->validator->validateRequestId($request);

        $plant = $this->repositoryCollection->plantRepository->getUserPlant(
            $this->user->getId(),
            $request->params['id']
        );

        $this->response->setCode(200);

        $this->response->setBodySingleResource($plant);

        $this->view->display($this->response);
    }

    /**
     * @param Request $request
     * @throws NotFound
     * @throws \Exception
     */
    public function delete(Request $request): void
    {
        $this->validator->validateRequestId($request);

        $this->repositoryCollection->plantRepository->deleteUserPlant(
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

        $plant = new Plant(
            null,
            $this->user->getId(),
            $request->params['englishName'],
            $request->params['latinName'],
            $request->params['imageLink']
        );

        $this->repositoryCollection->plantRepository->saveUserPlant($plant);

        $this->response->setCode(201);

        $this->response->setBodySingleResource($plant);

        $this->view->display($this->response);
    }

    /**
     * @param Request $request
     * @throws \Exception
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
            $this->repositoryCollection->plantRepository->updateUserPlant($plant);
        } catch (NotFound $e) {
            $this->repositoryCollection->plantRepository->saveUserPlant($plant);

            $this->response->setCode(201);
        }

        $this->response->setBodySingleResource($plant);

        $this->view->display($this->response);
    }
}