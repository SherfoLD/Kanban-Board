<?php
require_once "../data/DatabaseConnection.php";
require_once "../data/Singleton.php";

use PgSql\Result;
use PgSql\Connection;

class BoardRepository extends Singleton
{

    public function save(BoardEntity $boardEntity): Result|false
    {
        if ($boardEntity->getId() != null) {
            return pg_query_params(
                self::getConnection(),
                "UPDATE board SET team_id = $1, name = $2, created_at = $3 WHERE id = $4",
                array(
                    $boardEntity->getTeamId(),
                    $boardEntity->getName(),
                    $boardEntity->getCreatedAt(),
                    $boardEntity->getId()
                )
            );

        } else {
            return pg_query_params(
                self::getConnection(),
                "INSERT INTO board(team_id, name, created_at) VALUES ($1, $2, $3)",
                array(
                    $boardEntity->getTeamId(),
                    $boardEntity->getName(),
                    $boardEntity->getCreatedAt()
                )
            );
        }
    }

    public function findById($id): BoardEntity
    {
        $result = pg_query_params(
            self::getConnection(),
            "SELECT team_id, name, created_at FROM board WHERE id = $1",
            array($id)
        );
        $boardData = pg_fetch_assoc($result);

        return new BoardEntity(
            $id,
            $boardData['team_id'],
            $boardData['name'],
            $boardData['created_at']
        );
    }

    public function deleteById($id): Result|false
    {
        return pg_query_params(
            self::getConnection(),
            "DELETE FROM board WHERE id = $1",
            array($id)
        );
    }

    private static function getConnection(): Connection
    {
        return DatabaseConnection::getInstance()->getConnection();
    }
}
