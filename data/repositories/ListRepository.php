<?php

class ListRepository
{
    private static $instance;

    private function __construct()
    {
    }

    public static function getInstance(): ListRepository
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function save(ListEntity $listEntity)
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
                "INSERT INTO list(board_id, name, position, created_by, created_at) VALUES ($1, $2, $3, $4, $5)",
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

    public function findById($id) : ListEntity
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

    public function deleteById($id)
    {
        return pg_query_params(
            self::getConnection(),
            "DELETE FROM list WHERE id = $1",
            array($id)
        );
    }

    private static function getConnection()
    {
        return DatabaseConnection::getInstance()->getConnection();
    }
}
