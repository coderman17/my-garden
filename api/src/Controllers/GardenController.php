<?php

declare(strict_types = 1);

namespace MyGarden\Controllers;

use MyGarden\Models\User;

class GardenController extends Controller
{
    public function getUserGarden(): void
    {
        $this->repositoryCollection->gardenRepository->getUserGardenWithPlants();
        if($this->repositoryCollection->userRepository->getUserFromEmailAndPassword($email, $password)){
//            echo "This user already exists";
            return;
        };

        $user = new User(null, $username, $email, $password);

        $this->repositoryCollection->userRepository->storeUser($user);
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