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

class GardenController extends Controller
{

    /**
     * @throws OutOfRangeInt
     * @throws OverMaxChars
     * @throws UnderMinChars
     * @throws \Exception
     */
    public function getAll(): void
    {
        $userId = $this->user->getId();

        $gardenArray = $this->repositoryCollection->gardenRepository->getUserGardens($userId);

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
        $userId = $this->user->getId();

        $request->validateExistsWithType([
             'id' => [
                 'type' => 'string',
             ],
        ]);

        $gardenId = $request->params['id'];

        $garden = $this->repositoryCollection->gardenRepository->getUserGarden($userId, $gardenId);

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
        $userId = $this->user->getId();

        $request->validateExistsWithType([
             'id' => [
                 'type' => 'string',
             ],
        ]);

        $gardenId = $request->params['id'];

        $this->repositoryCollection->gardenRepository->deleteUserGarden($userId, $gardenId);

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
            'name' => [
                'type' => 'string',
            ],
            'dimensionX' => [
                'type' => 'integer',
            ],
            'dimensionY' => [
                'type' => 'integer',
            ],
        ]);

        $name = $request->params['name'];

        $dimensionX = $request->params['dimensionX'];

        $dimensionY = $request->params['dimensionY'];

        $garden = new Garden(null, $userId, $name, $dimensionX, $dimensionY);

        $garden = $this->repositoryCollection->gardenRepository->saveUserGarden($garden);

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
        $userId = $this->user->getId();

        $request->validateExistsWithType([
             'id' => [
                 'type' => 'string',
             ],
             'name' => [
                 'type' => 'string',
             ],
             'dimensionX' => [
                 'type' => 'integer',
             ],
             'dimensionY' => [
                 'type' => 'integer',
             ],
        ]);

        $id = $request->params['id'];

        $name = $request->params['name'];

        $dimensionX = $request->params['dimensionX'];

        $dimensionY = $request->params['dimensionY'];

        $garden = new Garden($id, $userId, $name, $dimensionX, $dimensionY);

        $garden = $this->repositoryCollection->gardenRepository->updateUserGarden($garden);

        $this->response->setCode(200);

        $this->response->setBodySingleResource($garden);

        $this->view->display($this->response);
    }
}