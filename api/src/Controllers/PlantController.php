<?php

declare(strict_types = 1);

namespace MyGarden\Controllers;

use MyGarden\Models\Plant;
use MyGarden\Models\User;
use MyGarden\Repositories\RepositoryCollection;
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

    public function get(int $plantId): Plant
    {
        //TODO get user from a check of who is logged in
        $userId = $this->user->getId();

        return $this->repositoryCollection->plantRepository->getUserPlant($userId, $plantId);
    }

    public function delete(int $plantId): void
    {
        //TODO get user from a check of who is logged in
        $userId = $this->user->getId();

        $this->repositoryCollection->plantRepository->deleteUserPlant($userId, $plantId);
    }

    public function store(string $englishName, string $latinName): Plant
    {
        //TODO get user from a check of who is logged in
        $userId = $this->user->getId();

        $plant = new Plant(null, $userId, $englishName, $latinName);

        return $this->repositoryCollection->plantRepository->saveUserPlant($userId, $plant);
    }

    public function update(int $plantId, string $englishName, string $latinName): Plant
    {
        //TODO get user from a check of who is logged in
        $userId = $this->user->getId();

        $plant = new Plant($plantId, $userId, $englishName, $latinName);

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