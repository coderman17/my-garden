<?php

declare(strict_types = 1);

namespace MyGarden\Database;

Class DatabaseConnection
{
    public \PDO $dbh;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
            $this->dbh = new \PDO(
                'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME'),
                getenv('DB_USERNAME'),
                getenv('DB_PASSWORD')
            );

            $this->dbh->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
    }
}