<?php

declare(strict_types = 1);

namespace MyGarden\Repositories;

use MyGarden\Database\DatabaseConnection;

class RepositoryCollection
{
    public DatabaseConnection $databaseConnection;
    public PlantRepository $plantRepository;
    public UserRepository $userRepository;

    public function __construct()
    {
        $this->plantRepository = new PlantRepository($this);
        $this->userRepository = new UserRepository($this);
    }
}