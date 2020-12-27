<?php

declare(strict_types = 1);

namespace MyGarden\Helpers;

class Helper
{
    /**
     * @param string $password
     * @return string
     * @throws \Exception
     */
    public static function hash(string $password): string
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if (!is_string($hashedPassword)){
            throw new \Exception('password could not be hashed');
        }

        return $hashedPassword;
    }

    public static function verifyHash(string $password, string $hash): bool
    {
        if (password_verify($password, $hash)) {
            return true;
        }

        return false;
    }
}