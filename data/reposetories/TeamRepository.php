<?php

class TeamRepository
{
    public function save(TeamEntity $teamEntity)
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
                "INSERT INTO team(name, created_at) VALUES ($1, $2)",
                array(
                    $teamEntity->getName(),
                    $teamEntity->getCreatedAt()
                )
            );
        }
    }

    public function findById($id)
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

    public function deleteById($id)
    {
        return pg_query_params(
            self::getConnection(),
            "DELETE FROM team WHERE id = $1",
            array($id)
        );
    }

    public static function getConnection()
    {
        return DatabaseConnection::getInstance()->getConnection();
    }
}