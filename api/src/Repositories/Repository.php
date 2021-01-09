<?php

declare(strict_types = 1);

namespace MyGarden\Repositories;

use MyGarden\Database\DatabaseConnection;

class Repository
{
    protected RepositoryCollection $repositoryCollection;

    protected DatabaseConnection $databaseConnection;

    public function __construct (RepositoryCollection $repositoryCollection = null)
    {
        if ($repositoryCollection !== null){
            $this->repositoryCollection = $repositoryCollection;
        }

        $this->databaseConnection = new DatabaseConnection();
    }

    /**
     * @param string $query
     * @return \PDOStatement
     * @throws \Exception
     */
    public function prepare(string $query): \PDOStatement
    {
        $stmt = $this->databaseConnection->dbh->prepare($query);

        if (!$stmt instanceOf \PDOStatement){
            throw new \Exception('Could not prepare database statement. ' . $this->databaseConnection->dbh->errorInfo()[2]);
        }

        return $stmt;
    }

    /**
     * @param array<string, string|int> $mapping
     * @param \PDOStatement $stmt
     * @param callable $unexpectedRowCount
     * @throws \Exception
     */
    public function execute(array $mapping, \PDOStatement $stmt, callable $unexpectedRowCount): void
    {
        $stmt->execute($mapping);

        if ($unexpectedRowCount($stmt->rowCount()) === true){
            throw new \Exception('An unexpected number of database rows were affected. Number actually affected: ' . $stmt->rowCount());
        }
    }
}