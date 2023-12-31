<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/data/DatabaseConnection.php";
require_once "$root/data/entities/TeamEntity.php";

use PgSql\Result;
use PgSql\Connection;

class TeamRepository
{
    private static self|null $instance = null;

    final private function __construct()
    {
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function save(TeamEntity $teamEntity): Result|false
    {
        if ($teamEntity->getId() != null) {
            return pg_query_params(
                self::getConnection(),
                "UPDATE team SET name = $1, created_at = $2 WHERE id = $3",
                array(
                    $teamEntity->getName(),
                    $teamEntity->getCreatedAt(),
                    $teamEntity->getId()
                )
            );

        } else {
            return pg_query_params(
                self::getConnection(),
                "INSERT INTO team(name, created_at) VALUES ($1, $2) RETURNING id",
                array(
                    $teamEntity->getName(),
                    $teamEntity->getCreatedAt()
                )
            );
        }
    }

    public function findById($id): TeamEntity
    {
        $result = pg_query_params(
            self::getConnection(),
            "SELECT name, created_at FROM team WHERE id = $1",
            array($id)
        );
        $teamData = pg_fetch_assoc($result);

        return new TeamEntity(
            $id,
            $teamData['name'],
            $teamData['created_at']
        );
    }

    public function deleteById($id): Result|false
    {
        return pg_query_params(
            self::getConnection(),
            "DELETE FROM team WHERE id = $1",
            array($id)
        );
    }

    private static function getConnection(): Connection
    {
        return DatabaseConnection::getInstance()->getConnection();
    }
}
