<?php
require_once "../data/DatabaseConnection.php";
require_once "../data/Singleton.php";

use PgSql\Result;
use PgSql\Connection;

class UserRepository extends Singleton
{

    public function save(UserEntity $userEntity): Result|false
    {
        if ($userEntity->getId() != null) {
            return pg_query_params(
                self::getConnection(),
                "UPDATE \"user\" SET email = $1, first_name = $2, last_name = $3, created_at = $4 WHERE id = $5",
                array(
                    $userEntity->getEmail(),
                    $userEntity->getFirstName(),
                    $userEntity->getLastName(),
                    $userEntity->getCreatedAt(),
                    $userEntity->getId()
                )
            );

        } else {
            return pg_query_params(
                self::getConnection(),
                "INSERT INTO \"user\"(email, first_name, last_name, created_at) VALUES ($1, $2, $3, $4) RETURNING id",
                array(
                    $userEntity->getEmail(),
                    $userEntity->getFirstName(),
                    $userEntity->getLastName(),
                    $userEntity->getCreatedAt()
                )
            );
        }
    }

    public function findById($id): UserEntity
    {
        $result = pg_query_params(
            self::getConnection(),
            "SELECT email, first_name, last_name, created_at FROM \"user\" WHERE id = $1",
            array($id)
        );
        $userData = pg_fetch_assoc($result);

        return new UserEntity(
            $id,
            $userData['email'],
            $userData['first_name'],
            $userData['last_name'],
            $userData['created_at']
        );
    }

    public function deleteById($id): Result|false
    {
        return pg_query_params(
            self::getConnection(),
            "DELETE FROM \"user\" WHERE id = $1",
            array($id)
        );
    }

    private function getConnection(): false|Connection
    {
        return DatabaseConnection::getInstance()->getConnection();
    }
}
