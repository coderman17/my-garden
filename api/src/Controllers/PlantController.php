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

class PlantController extends Controller
{
    /**
     * @throws \Exception
     */
    public function getAll(): void
    {
        $userId = $this->user->getId();

        $plantArray = $this->repositoryCollection->plantRepository->getUserPlants($userId);

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
        $userId = $this->user->getId();

        $plantId = $request->params['id'];

        $plant = $this->repositoryCollection->plantRepository->getUserPlant($userId, $plantId);

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
        $userId = $this->user->getId();

        $plantId = $request->params['id'];

        $this->repositoryCollection->plantRepository->deleteUserPlant($userId, $plantId);

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
        $userId = $this->user->getId();

        $request->validateExistsWithType([
            'englishName' => [
                'type' => 'string',
            ],
            'latinName' => [
                'type' => 'string',
            ],
            'imageLink' => [
                'type' => 'string',
            ],
        ]);

        $englishName = $request->params['englishName'];

        $latinName = $request->params['latinName'];

        $imageLink = $request->params['imageLink'];

        $plant = new Plant(null, $userId, $englishName, $latinName, $imageLink);

        $plant = $this->repositoryCollection->plantRepository->saveUserPlant($userId, $plant);

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
        $userId = $this->user->getId();

        $request->validateExistsWithType([
            'id' => [
                'type' => 'integer',
            ],
             'englishName' => [
                 'type' => 'string',
             ],
             'latinName' => [
                 'type' => 'string',
             ],
             'imageLink' => [
                 'type' => 'string',
             ],
        ]);

        $plantId = $request->params['id'];

        $englishName = $request->params['englishName'];

        $latinName = $request->params['latinName'];

        $imageLink = $request->params['imageLink'];

        $plant = new Plant($plantId, $userId, $englishName, $latinName, $imageLink);

        $plant = $this->repositoryCollection->plantRepository->updateUserPlant($userId, $plant);

        $this->response->setCode(200);

        $this->response->setBodySingleResource($plant);

        $this->view->display($this->response);
    }
}