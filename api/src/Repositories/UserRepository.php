<?php

declare(strict_types = 1);

namespace MyGarden\Repositories;

use MyGarden\Exceptions\OutOfRangeInt;
use MyGarden\Exceptions\OverMaxChars;
use MyGarden\Exceptions\UnderMinChars;
use MyGarden\Helpers\Helper;
use MyGarden\Models\User;

class UserRepository extends Repository
{
    /**
     * @param string $email
     * @param string $password
     * @return User
     * @throws OutOfRangeInt
     * @throws OverMaxChars
     * @throws UnderMinChars
     * @throws \Exception
     */
    public function getUserFromEmailAndPassword(string $email, string $password): User
    {
        $stmt = $this->repositoryCollection->databaseConnection->dbh->prepare(
            'SELECT *
            FROM users
            WHERE `email` = :email'
        );

        if (!$stmt instanceOf \PDOStatement){
            throw new \Exception('Could not prepare database statement');
        }

        $stmt->execute([
           'email' => $email,
       ]);

        $row = $stmt->fetch(\PDO::FETCH_OBJ);

        if ($row === false || Helper::verifyHash($password, $row->password) === false){
            throw new \Exception('Could not verify user');
        }

        return new User(
            $row->id,
            $row->username,
            $row->email,
            $row->password
        );
    }
}