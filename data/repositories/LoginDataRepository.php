<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/data/DatabaseConnection.php";
require_once "$root/data/entities/LoginDataEntity.php";

use PgSql\Result;
use PgSql\Connection;

class LoginDataRepository
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

    public function save(LoginDataEntity $loginDataEntity): Result|false
    {
        if ($loginDataEntity->getId() != null) {
            return pg_query_params(
                self::getConnection(),
                "UPDATE login_data SET user_id = $1, email = $2, \"password\" = $3, blocked = $4 WHERE id = $5",
                array(
                    $loginDataEntity->getUserId(),
                    $loginDataEntity->getEmail(),
                    $loginDataEntity->getPassword(),
                    $loginDataEntity->getBlocked(),
                    $loginDataEntity->getId()
                )
            );

        } else {
            return pg_query_params(
                self::getConnection(),
                "INSERT INTO login_data(user_id, email, \"password\", blocked) VALUES ($1, $2, $3, $4) RETURNING id",
                array(
                    $loginDataEntity->getUserId(),
                    $loginDataEntity->getEmail(),
                    $loginDataEntity->getPassword(),
                    $loginDataEntity->getBlocked()
                )
            );
        }
    }

    public function findById($id): LoginDataEntity
    {
        $result = pg_query_params(
            self::getConnection(),
            "SELECT user_id, email, \"password\" FROM login_data WHERE id = $1",
            array($id)
        );
        $loginData = pg_fetch_assoc($result);

        return new LoginDataEntity(
            $id,
            $loginData['user_id'],
            $loginData['email'],
            $loginData['password'],
            $loginData['blocked']
        );
    }

    public function isUserBlockedByUserId($userId) : bool
    {
        $result = pg_query_params(
            self::getConnection(),
            "SELECT blocked FROM login_data WHERE user_id = $1",
            array($userId)
        );
        if (!$result)
            return true;

        $loginData = pg_fetch_assoc($result);

        return $loginData['blocked'] == 1;
    }

    public function findUserByEmailAndPassword($email, $password)
    {
        $result = pg_query_params(
            self::getConnection(),
            "SELECT user_id FROM login_data WHERE email = $1 and \"password\" = $2",
            array($email, $password)
        );
        if (!$result)
            return false;

        $loginData = pg_fetch_assoc($result);

        return $loginData['user_id'];
    }

    public function findByUserId($userId): LoginDataEntity
    {
        $result = pg_query_params(
            self::getConnection(),
            "SELECT id, email, \"password\", blocked FROM login_data WHERE user_id = $1",
            array($userId)
        );
        $loginData = pg_fetch_assoc($result);

        return new LoginDataEntity(
            $loginData['id'],
            $userId,
            $loginData['email'],
            $loginData['password'],
            $loginData['blocked']
        );
    }

    public function deleteById($id): Result|false
    {
        return pg_query_params(
            self::getConnection(),
            "DELETE FROM login_data WHERE id = $1",
            array($id)
        );
    }

    private static function getConnection(): Connection
    {
        return DatabaseConnection::getInstance()->getConnection();
    }
}
