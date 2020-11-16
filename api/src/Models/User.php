<?php

declare(strict_types = 1);

namespace MyGarden\Models;

use MyGarden\Helpers\Helper;
use MyGarden\Repositories\GardenRepository;

class User extends Model
{
    protected ?int $id;

    protected string $username;

    protected string $email;

    protected string $password;

    public string $test = "this is just a test";

    public function __construct(?int $id, string $username, string $email, string $password)
    {
        $hashedPassword = Helper::hash($password);

        $this->validate([
            ['id', $id > 0 && $id < 4294967295 || $id === null],
            ['username', 0 < strlen($username) && strlen($username) < 30],
            ['email', 0 < strlen($email) && strlen($email) < 80 && preg_match("/.+@.+\..+/", $email)],
            ['password', 0 < strlen($hashedPassword) && strlen($hashedPassword) < 255]
        ]);

        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $hashedPassword;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }


//    public function getGarden(): Garden
//    {
//        return $this->gardenRepository->getUserGardenWithPlants($this->id);
//    }
}