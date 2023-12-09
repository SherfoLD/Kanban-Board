<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/data/DatabaseConnection.php";
require_once "$root/data/entities/UserEntity.php";

use PgSql\Result;
use PgSql\Connection;

class UserRepository
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

    public function findById($id): UserEntity|false
    {
        $result = pg_query_params(
            self::getConnection(),
            "SELECT email, first_name, last_name, created_at FROM \"user\" WHERE id = $1",
            array($id)
        );
        if(!$result)
            return false;

        $userData = pg_fetch_assoc($result);

        return new UserEntity(
            $id,
            $userData['email'],
            $userData['first_name'],
            $userData['last_name'],
            $userData['created_at']
        );
    }

    public function findByEmail($email): UserEntity|false
    {
        $result = pg_query_params(
            self::getConnection(),
            "SELECT id, first_name, last_name, created_at FROM \"user\" WHERE email = $1",
            array($email)
        );
        if(!$result)
            return false;

        $userData = pg_fetch_assoc($result);
        if ($userData == null)
            return false;

        return new UserEntity(
            $userData['id'],
            $email,
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
