<?php
/** @noinspection PhpUnused */ //will be used in future, TODO - instantiate proper login and auth

declare(strict_types = 1);

namespace MyGarden\Auth;

use MyGarden\Exceptions\OverMaxChars;
use MyGarden\Exceptions\UnderMinChars;
use MyGarden\Models\User;
use MyGarden\Repositories\Repository;

class Auth
{
    protected Repository $repository;

    public function __construct()
    {
        $this->repository = new Repository();
    }

    public static function user(): User
    {
        /** @noinspection SpellCheckingInspection */
        return new User(
            'MG21ea8ea135b2g',
            'dan',
            'dan@email.com',
            '$2y$10$W4ixd.rF03iRVuECIP1Hu.yVH/eyStgiKgTmOqqEHBI9vuSoYnxvi' // = 'password'
        );
    }

    /**
     * @param string $email
     * @param string $password
     * @return User
     * @throws OverMaxChars
     * @throws UnderMinChars
     * @throws \Exception
     */
    public function getAuthenticatedUser(string $email, string $password): User
    {
        $stmt = $this->repository->prepare(
            'SELECT *
            FROM users
            WHERE `email` = :email'
        );

        $this->repository->execute(
            [
                'email' => $email
            ],
            $stmt,
            function ($rowCount){ return $rowCount > 1; }
        );

        $row = $stmt->fetch(\PDO::FETCH_OBJ);

        if ($row === false || $this->verifyHash($password, $row->password) === false){
            throw new \Exception('Could not verify user');
        }

        return new User(
            $row->id,
            $row->username,
            $row->email,
            $row->password
        );
    }

    /**
     * @param string $password
     * @return string
     * @throws \Exception
     */
    protected function hash(string $password): string
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if (!is_string($hashedPassword)){
            throw new \Exception('password could not be hashed');
        }

        return $hashedPassword;
    }

    protected function verifyHash(string $password, string $hash): bool
    {
        if (password_verify($password, $hash)) {
            return true;
        }

        return false;
    }

}