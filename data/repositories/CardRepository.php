<?php

class CardRepository
{
    private static $instance;

    private function __construct()
    {
    }

    public static function getInstance(): CardRepository
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function save(CardEntity $cardEntity)
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
                "INSERT INTO card(list_id, name, position, created_by, created_at) VALUES ($1, $2, $3, $4, $5)",
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

    public function deleteById($id)
    {
        return pg_query_params(
            self::getConnection(),
            "DELETE FROM card WHERE id = $1",
            array($id)
        );
    }

    private static function getConnection()
    {
        return DatabaseConnection::getInstance()->getConnection();
    }
}
