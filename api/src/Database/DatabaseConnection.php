<?php

declare(strict_types=1);

namespace MyGarden\Database;

use MyGarden\Singleton;

class DatabaseConnection extends Singleton
{
    public \PDO $dbh;

    /**
     * @throws \Exception
     */
    protected function init(): void
    {
        $dbHost = getenv('DB_HOST');

        $dbName = getenv("DB_NAME");

        $dbUsername = getenv("DB_USERNAME");

        $dbPassword = getenv("DB_PASSWORD");

        if (!$dbHost || !$dbName || !$dbUsername || !$dbPassword) {
            throw new \Exception('Some environment configuration was not found');
        }

        $this->dbh = new \PDO(
            'mysql:host=' . $dbHost . ';dbname=' . $dbName,
            $dbUsername,
            $dbPassword
        );

        $this->dbh->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
    }
}
