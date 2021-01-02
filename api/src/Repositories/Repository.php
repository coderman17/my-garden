<?php

declare(strict_types = 1);

namespace MyGarden\Repositories;

abstract class Repository
{
    protected RepositoryCollection $repositoryCollection;

    public function __construct (RepositoryCollection $repositoryCollection)
    {
        $this->repositoryCollection = $repositoryCollection;
    }

    /**
     * @param string $query
     * @return \PDOStatement
     * @throws \Exception
     */
    protected function prepare(string $query): \PDOStatement
    {
        $stmt = $this->repositoryCollection->databaseConnection->dbh->prepare($query);

        if (!$stmt instanceOf \PDOStatement){
            throw new \Exception('Could not prepare database statement. ' . $this->repositoryCollection->databaseConnection->dbh->errorInfo()[2]);
        }

        return $stmt;
    }

    /**
     * @param array<string, string|int> $mapping
     * @param \PDOStatement $stmt
     * @param callable $unexpectedRowCount
     * @throws \Exception
     */
    protected function execute(array $mapping, \PDOStatement $stmt, callable $unexpectedRowCount): void
    {
        $stmt->execute($mapping);

        if ($unexpectedRowCount($stmt->rowCount()) === true){
            throw new \Exception('An unexpected number of database rows were affected. Number actually affected: ' . $stmt->rowCount());
        }
    }
}