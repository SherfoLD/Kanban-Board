<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/data/DatabaseConnection.php";
require_once "$root/data/entities/TeamUserEntity.php";

use PgSql\Result;
use PgSql\Connection;

class TeamUserRepository
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

    public function save(TeamUserEntity $teamUserEntity): Result|false
    {
        if ($teamUserEntity->getId() != null) {
            return pg_query_params(
                self::getConnection(),
                "UPDATE team_user SET user_id = $1, team_id = $2, role = $3 WHERE id = $4",
                array(
                    $teamUserEntity->getUserId(),
                    $teamUserEntity->getTeamId(),
                    $teamUserEntity->getRole(),
                    $teamUserEntity->getId()
                )
            );
        } else {
            return pg_query_params(
                self::getConnection(),
                "INSERT INTO team_user(user_id, team_id, role) VALUES ($1, $2, $3) RETURNING id",
                array(
                    $teamUserEntity->getUserId(),
                    $teamUserEntity->getTeamId(),
                    $teamUserEntity->getRole()
                )
            );
        }
    }

    public function findById($id): TeamUserEntity
    {
        $result = pg_query_params(
            self::getConnection(),
            "SELECT user_id, team_id, role FROM team_user WHERE id = $1",
            array($id)
        );
        $teamUserData = pg_fetch_assoc($result);

        return new TeamUserEntity(
            $id,
            $teamUserData['user_id'],
            $teamUserData['team_id'],
            $teamUserData['role']
        );
    }

    public function findByTeamIdAndUserId($teamId, $userId): TeamUserEntity
    {
        $result = pg_query_params(
            self::getConnection(),
            "SELECT id, role FROM team_user WHERE team_id = $1 AND user_id = $2",
            array($teamId, $userId)
        );
        $teamUserData = pg_fetch_assoc($result);

        return new TeamUserEntity(
            $teamUserData['id'],
            $userId,
            $userId,
            $teamUserData['role']
        );
    }

    public function deleteById($id): Result|false
    {
        return pg_query_params(
            self::getConnection(),
            "DELETE FROM team_user WHERE id = $1",
            array($id)
        );
    }

    public function fetchAllTeamsByUserId($userId): Result|false
    {
        return pg_query_params(
            self::getConnection(),
            "SELECT team_id FROM team_user WHERE user_id = $1",
            array($userId)
        );
    }

    public function fetchAllUsersByTeamId($teamId): Result|false
    {
        return pg_query_params(
            self::getConnection(),
            "SELECT user_id FROM team_user WHERE team_id = $1",
            array($teamId)
        );
    }

    private static function getConnection(): Connection
    {
        return DatabaseConnection::getInstance()->getConnection();
    }
}
