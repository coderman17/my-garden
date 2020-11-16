<?php

declare(strict_types = 1);

namespace MyGarden\Database;

Class DatabaseConnection
{
    protected $dbh;

    public function __construct()
    {
//        phpinfo();
        try {
            $this->dbh = new \PDO(
                'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME'),
                getenv('DB_USERNAME'),
                getenv('DB_PASSWORD')
            );

        } catch (\PDOException $e) {
//            throw new \Exception("Couldn't connect to database");
            throw new \Exception($e->getMessage());
        }

        $this->dbh->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
    }

    public function prepare(string $stmt)
    {
        return $this->dbh->prepare($stmt);
    }
}