<?php

declare(strict_types = 1);

namespace MyGarden\Models;

use MyGarden\Exceptions\OverMaxChars;
use MyGarden\Exceptions\UnderMinChars;
use MyGarden\Helpers\Helper;

class User extends Model
{
    protected string $id;

    protected string $username;

    protected string $email;

    protected string $password;

    /**
     * @param string|null $id
     * @param string $username
     * @param string $email
     * @param string $password
     * @throws OverMaxChars
     * @throws UnderMinChars
     * @throws \Exception
     */
    public function __construct(?string $id, string $username, string $email, string $password)
    {
        if ($id !== null) {
            $this->validateParamStringLength('id', $id, Model::UUID_LENGTH, Model::UUID_LENGTH);

            $this->id = $id;
        } else {
            $this->id = uniqid();
        }

        $this->validateParamStringLength('username', $username, 1, 30);

        $this->username = $username;

        //TODO validate email format
        $this->validateParamStringLength('email', $email, 6, 80);

        $this->email = $email;

        $hashedPassword = Helper::hash($password);

        $this->password = $hashedPassword;
    }

    /**
     * @return string
     */
    public function getId(): string
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