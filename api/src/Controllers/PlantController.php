<?php

declare(strict_types = 1);

namespace MyGarden\Controllers;

use MyGarden\Models\Plant;
use MyGarden\Request\Request;
use MyGarden\Response\Response;

class PlantController extends Controller
{
    public function getAll(): void
    {
        $userId = $this->user->getId();

        $plantArray = $this->repositoryCollection->plantRepository->getUserPlants($userId);

        $response = new Response(
            200,
            $plantArray->getItems()
        );

        $this->view->display($response);
    }

    public function get(Request $request): Plant
    {
        $userId = $this->user->getId();

        $plantId = $request->params['id'];

        $plantId = $request->validateInteger($plantId);

        $plant = $this->repositoryCollection->plantRepository->getUserPlant($userId, $plantId);

        $response = new Response(
            200,
            $plant
        );

        $this->view->display($response);
    }

    public function delete(Request $request): void
    {
        $userId = $this->user->getId();

        $plantId = $request->params['id'];

        $plantId = $request->validateInteger($plantId);

        $this->repositoryCollection->plantRepository->deleteUserPlant($userId, $plantId);

        $response = new Response(
            204,
            ''
        );

        $this->view->display($response);
    }

    public function store(Request $request): Plant
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

        $response = new Response(
            201,
            $plant
        );

        $this->view->display($response);
    }

    public function update(Request $request): Plant
    {
        $userId = $this->user->getId();

        $plantId = $request->params['id'];

        $plantId = $request->validateInteger($plantId);

        $englishName = $request->params['englishName'];

        $latinName = $request->params['latinName'];

        $imageLink = $request->params['imageLink'];

        $plant = new Plant($plantId, $userId, $englishName, $latinName, $imageLink);

        $plant = $this->repositoryCollection->plantRepository->updateUserPlant($userId, $plant);

        $response = new Response(
            200,
            $plant
        );

        $this->view->display($response);
    }
}