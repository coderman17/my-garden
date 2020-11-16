<?php

declare(strict_types = 1);

namespace MyGarden\Repositories;

use MyGarden\Database\DatabaseConnection;

class RepositoryCollection
{
    public DatabaseConnection $databaseConnection;
    public GardenRepository $gardenRepository;
    public PlantRepository $plantRepository;
    public UserRepository $userRepository;

    public function __construct(DatabaseConnection $databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
        $this->gardenRepository = new GardenRepository($this);
        $this->plantRepository = new PlantRepository($this);
        $this->userRepository = new UserRepository($this);
    }
}