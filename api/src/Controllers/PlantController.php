<?php

declare(strict_types = 1);

namespace MyGarden\Controllers;

use MyGarden\Models\Plant;
use MyGarden\Models\User;
use MyGarden\Repositories\RepositoryCollection;
use MyGarden\Request\Request;
use MyGarden\TypedArrays\IntToPlantArray;

class PlantController extends Controller
{
    public User $user;

    public function __construct(RepositoryCollection $repositoryCollection, User $user)
    {
        parent::__construct($repositoryCollection);

        $this->user = $user;
    }

    public function getAll(): IntToPlantArray
    {
        //TODO get user from a check of who is logged in
        $userId = $this->user->getId();

        return $this->repositoryCollection->plantRepository->getUserPlants($userId);
    }

    public function get(Request $request): Plant
    {
        $plantId = $request->params['id'];

        $plantId = $request->validateInteger($plantId);

        //TODO get user from a check of who is logged in
        $userId = $this->user->getId();

        return $this->repositoryCollection->plantRepository->getUserPlant($userId, $plantId);
    }

    public function delete(Request $request): void
    {
        //TODO get user from a check of who is logged in
        $userId = $this->user->getId();

        $plantId = $request->params['id'];

        $plantId = $request->validateInteger($plantId);

        $this->repositoryCollection->plantRepository->deleteUserPlant($userId, $plantId);
    }

    public function store(Request $request): Plant
    {
        //TODO get user from a check of who is logged in
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

        return $this->repositoryCollection->plantRepository->saveUserPlant($userId, $plant);
    }

    public function update(Request $request): Plant
    {
        //TODO get user from a check of who is logged in
        $userId = $this->user->getId();

        $plantId = $request->params['id'];

        $plantId = $request->validateInteger($plantId);

        $englishName = $request->params['englishName'];

        $latinName = $request->params['latinName'];

        $imageLink = $request->params['imageLink'];

        $plant = new Plant($plantId, $userId, $englishName, $latinName, $imageLink);

        return $this->repositoryCollection->plantRepository->updateUserPlant($userId, $plant);
    }

    public function getUserFromEmailAndPassword(string $email, string $password): User
    {
        $user = $this->repositoryCollection->userRepository->getUserFromEmailAndPassword($email, $password);

        if ($user === false){
            throw new \Exception("The email and password combination didn't match our records");
        }

        return $user;
    }
}