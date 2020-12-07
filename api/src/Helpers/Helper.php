<?php

declare(strict_types = 1);

namespace MyGarden\Helpers;

class Helper
{
    static public function hash(string $password): string
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if ($hashedPassword == null || $hashedPassword == false){
            throw new \Exception("password could not be hashed");
        }

        return $hashedPassword;
    }

    static public function verifyHash(string $password, string $hash): bool
    {
        if (password_verify($password, $hash)) {
            return true;
        }

        return false;
    }
}