<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/data/DatabaseConnection.php";
require_once "$root/data/entities/CardEntity.php";

use PgSql\Result;
use PgSql\Connection;

class CardRepository
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

    public function save(CardEntity $cardEntity): Result|false
    {
        if ($cardEntity->getId() != null) {
            return pg_query_params(
                self::getConnection(),
                "UPDATE card SET list_id = $1, name = $2, position = $3, created_by = $4, created_at = $5 WHERE id = $6",
                array(
                    $cardEntity->getListId(),
                    $cardEntity->getName(),
                    $cardEntity->getPosition(),
                    $cardEntity->getCreatedBy(),
                    $cardEntity->getCreatedAt(),
                    $cardEntity->getId()
                )
            );

        } else {
            return pg_query_params(
                self::getConnection(),
                "INSERT INTO card(list_id, name, position, created_by, created_at) VALUES ($1, $2, $3, $4, $5) RETURNING id",
                array(
                    $cardEntity->getListId(),
                    $cardEntity->getName(),
                    $cardEntity->getPosition(),
                    $cardEntity->getCreatedBy(),
                    $cardEntity->getCreatedAt()
                )
            );
        }
    }

    public function findLastPositionByListId($listId): int
    {
        $query = pg_query_params(
            self::getConnection(),
            "SELECT position FROM card WHERE list_id = $1 ORDER BY position DESC LIMIT 1 ",
            array($listId)
        );

        $result = pg_fetch_all($query);

        return $result == null ? 0 : $result[0]['position'];
    }

    public function findById($id): CardEntity
    {
        $result = pg_query_params(
            self::getConnection(),
            "SELECT list_id, name, position, created_by, created_at FROM card WHERE id = $1",
            array($id)
        );
        $cardData = pg_fetch_assoc($result);

        return new CardEntity(
            $id,
            $cardData['list_id'],
            $cardData['name'],
            $cardData['position'],
            $cardData['created_by'],
            $cardData['created_at']
        );
    }

    public function fetchAllByListId($listId): Result|false
    {
        return pg_query_params(
            self::getConnection(),
            "SELECT id, name, created_by FROM card WHERE list_id = $1 ORDER BY position",
            array($listId)
        );
    }

    public function deleteById($id): Result|false
    {
        return pg_query_params(
            self::getConnection(),
            "DELETE FROM card WHERE id = $1",
            array($id)
        );
    }

    private static function getConnection(): Connection
    {
        return DatabaseConnection::getInstance()->getConnection();
    }
}
