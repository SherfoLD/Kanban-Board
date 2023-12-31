<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/data/DatabaseConnection.php";
require_once "$root/data/entities/ListEntity.php";

use PgSql\Result;
use PgSql\Connection;

class ListRepository
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

    public function save(ListEntity $listEntity): Result|false
    {
        if ($listEntity->getId() != null) {
            return pg_query_params(
                self::getConnection(),
                "UPDATE list SET board_id = $1, name = $2, position = $3, created_by = $4, created_at = $5 WHERE id = $6",
                array(
                    $listEntity->getBoardId(),
                    $listEntity->getName(),
                    $listEntity->getPosition(),
                    $listEntity->getCreatedBy(),
                    $listEntity->getCreatedAt(),
                    $listEntity->getId()
                )
            );
        } else {
            return pg_query_params(
                self::getConnection(),
                "INSERT INTO list(board_id, name, position, created_by, created_at) VALUES ($1, $2, $3, $4, $5) RETURNING id",
                array(
                    $listEntity->getBoardId(),
                    $listEntity->getName(),
                    $listEntity->getPosition(),
                    $listEntity->getCreatedBy(),
                    $listEntity->getCreatedAt()
                )
            );
        }
    }

    public function findById($id): ListEntity
    {
        $result = pg_query_params(
            self::getConnection(),
            "SELECT board_id, name, position, created_by, created_at FROM list WHERE id = $1",
            array($id)
        );
        $listData = pg_fetch_assoc($result);

        return new ListEntity(
            $id,
            $listData['board_id'],
            $listData['name'],
            $listData['position'],
            $listData['created_by'],
            $listData['created_at']
        );
    }

    public function fetchAllByBoardId($boardId): Result|false
    {
        return pg_query_params(
            self::getConnection(),
            "SELECT id, name, created_by FROM list WHERE board_id = $1 ORDER BY position",
            array($boardId)
        );
    }

    public function findLastPositionByBoardId($boardId): int
    {
        $query = pg_query_params(
            self::getConnection(),
            "SELECT position FROM list WHERE board_id = $1 ORDER BY position DESC LIMIT 1 ",
            array($boardId)
        );

        $result = pg_fetch_all($query);

        return $result == null ? 0 : $result[0]['position'];
    }

    public function findByBoardIdAndPosition($boardId, $position): ListEntity|false
    {
        $query = pg_query_params(
            self::getConnection(),
            "SELECT id, name, created_by, created_at FROM list WHERE board_id = $1 AND position = $2",
            array($boardId, $position)
        );

        $listData = pg_fetch_assoc($query);
        if (!$listData)
            return false;

        return new ListEntity(
            $listData['id'],
            $boardId,
            $listData['name'],
            $position,
            $listData['created_by'],
            $listData['created_at']
        );
    }

    public function deleteById($id): Result|false
    {
        return pg_query_params(
            self::getConnection(),
            "DELETE FROM list WHERE id = $1",
            array($id)
        );
    }

    private static function getConnection(): Connection
    {
        return DatabaseConnection::getInstance()->getConnection();
    }
}
