<?php

declare(strict_types = 1);

namespace MyGarden\Models;

use MyGarden\Exceptions\OutOfRangeInt;
use MyGarden\Exceptions\OverMaxChars;
use MyGarden\Exceptions\UnderMinChars;
use MyGarden\Helpers\Helper;

class User extends Model
{
    protected ?int $id;

    protected string $username;

    protected string $email;

    protected string $password;

    /**
     * @param int|null $id
     * @param string $username
     * @param string $email
     * @param string $password
     * @throws OutOfRangeInt
     * @throws OverMaxChars
     * @throws UnderMinChars
     * @throws \Exception
     */
    public function __construct(?int $id, string $username, string $email, string $password)
    {
        if ($id !== null) {
            $this->validateParamIntRange('id', $id, 0, Model::UNSIGNED_INT_MAX);
        }

        $this->id = $id;

        $this->validateParamStringLength('username', $username, 1, 30);

        $this->username = $username;

        //TODO validate email format
        $this->validateParamStringLength('email', $email, 6, 80);

        $this->email = $email;

        $hashedPassword = Helper::hash($password);

        $this->password = $hashedPassword;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
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

    public function mapJson(): array
    {
        return [
            'username' => $this->getUsername()
        ];
    }
}