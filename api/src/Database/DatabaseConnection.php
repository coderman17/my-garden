<?php

declare(strict_types = 1);

namespace MyGarden\Database;

Class DatabaseConnection
{
    public \PDO $dbh;

    public function __construct()
    {
        try {
            $this->dbh = new \PDO(
                'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME'),
                getenv('DB_USERNAME'),
                getenv('DB_PASSWORD')
            );

        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }

        $this->dbh->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
    }
}