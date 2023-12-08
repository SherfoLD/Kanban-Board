<?php
require_once "../data/DatabaseConnection.php";
require_once "../data/Singleton.php";

use PgSql\Result;
use PgSql\Connection;

class TeamUserRepository extends Singleton
{

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
                "INSERT INTO team_user(user_id, team_id, role) VALUES ($1, $2, $3)",
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

    public function deleteById($id): Result|false
    {
        return pg_query_params(
            self::getConnection(),
            "DELETE FROM team_user WHERE id = $1",
            array($id)
        );
    }

    private static function getConnection(): Connection
    {
        return DatabaseConnection::getInstance()->getConnection();
    }
}
