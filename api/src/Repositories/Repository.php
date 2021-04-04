<?php

declare(strict_types=1);

namespace MyGarden\Repositories;

use MyGarden\Database\DatabaseConnection;
use MyGarden\Exceptions\ConstructionFailure;
use MyGarden\Models\Garden;
use MyGarden\Models\Plant;
use MyGarden\Models\PlantLocation;
use MyGarden\Singleton;

class Repository extends Singleton
{
    protected DatabaseConnection $databaseConnection;

    protected function init(): void
    {
        /** @noinspection PhpFieldAssignmentTypeMismatchInspection */
        $this->databaseConnection = DatabaseConnection::getInstance();
    }

    /**
     * @param  string $query
     * @return \PDOStatement
     * @throws \Exception
     */
    public function prepare(string $query): \PDOStatement
    {
        $stmt = $this->databaseConnection->dbh->prepare($query);

        if (!$stmt instanceof \PDOStatement) {
            throw new \Exception(
                'Could not prepare database statement. ' . $this->databaseConnection->dbh->errorInfo()[2]
            );
        }

        return $stmt;
    }

    /**
     * @param  array<string, string|int> $mapping
     * @param  \PDOStatement             $stmt
     * @param  callable                  $unexpectedRowCount
     * @throws \Exception
     */
    public function execute(array $mapping, \PDOStatement $stmt, callable $unexpectedRowCount): void
    {
        $stmt->execute($mapping);

        if ($unexpectedRowCount($stmt->rowCount()) === true) {
            throw new \Exception(
                'An unexpected number of database rows were affected. Number actually affected: ' . $stmt->rowCount()
            );
        }
    }

    /**
     * @param  array<string, string> $columnAliases
     * @return string
     */
    public function constructQueryFromAliases(array $columnAliases): string
    {
        $string = '';

        foreach ($columnAliases as $column => $alias) {
            $string .= $column . ' as ' . $alias . ', ';
        }

        return substr($string, 0, -2);
    }

    /**
     * @param  array $row
     * @return Garden
     * @throws ConstructionFailure
     *
     * @phpstan-ignore-next-line //haven't specified types of $row, they are various. We catch Throwable anyway
     */
    public function gardenFromRow(array $row): Garden
    {
        try {
            return new Garden(
                $row[Garden::COLUMN_ALIASES['gardens.id']],
                $row[Garden::COLUMN_ALIASES['gardens.user_id']],
                $row[Garden::COLUMN_ALIASES['gardens.name']],
                $row[Garden::COLUMN_ALIASES['gardens.dimension_x']],
                $row[Garden::COLUMN_ALIASES['gardens.dimension_y']]
            );
        } catch (\Throwable $e) {
            throw new ConstructionFailure($e);
        }
    }

    /**
     * @param  array $row
     * @return Plant
     * @throws ConstructionFailure
     *
     * @phpstan-ignore-next-line //haven't specified types of $row, they are various. We catch Throwable anyway
     */
    public function plantFromRow(array $row): Plant
    {
        try {
            return new Plant(
                $row[Plant::COLUMN_ALIASES['plants.id']],
                $row[Plant::COLUMN_ALIASES['plants.user_id']],
                $row[Plant::COLUMN_ALIASES['plants.english_name']],
                $row[Plant::COLUMN_ALIASES['plants.latin_name']],
                $row[Plant::COLUMN_ALIASES['plants.image_link']]
            );
        } catch (\Throwable $e) {
            throw new ConstructionFailure($e);
        }
    }

    /**
     * @param  array $row
     * @return PlantLocation
     * @throws ConstructionFailure
     *
     * @phpstan-ignore-next-line //haven't specified types of $row, they are various. We catch Throwable anyway
     */
    public function plantLocationFromRow(array $row): PlantLocation
    {
        try {
            return new PlantLocation(
                $row[PlantLocation::COLUMN_ALIASES['gardens_plants.plant_id']],
                $row[PlantLocation::COLUMN_ALIASES['gardens_plants.coordinate_x']],
                $row[PlantLocation::COLUMN_ALIASES['gardens_plants.coordinate_y']]
            );
        } catch (\Throwable $e) {
            throw new ConstructionFailure($e);
        }
    }
}
