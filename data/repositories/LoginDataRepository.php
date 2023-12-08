<?php
require_once "../data/DatabaseConnection.php";
require_once "../data/Singleton.php";

use PgSql\Result;
use PgSql\Connection;

class LoginDataRepository extends Singleton
{

    public function save(LoginDataEntity $loginDataEntity): Result|false
    {
        if ($loginDataEntity->getId() != null) {
            return pg_query_params(
                self::getConnection(),
                "UPDATE login_data SET user_id = $1, email = $2, \"password\" = $3 WHERE id = $4",
                array(
                    $loginDataEntity->getUserId(),
                    $loginDataEntity->getEmail(),
                    $loginDataEntity->getPassword(),
                    $loginDataEntity->getId()
                )
            );

        } else {
            return pg_query_params(
                self::getConnection(),
                "INSERT INTO login_data(user_id, email, \"password\") VALUES ($1, $2, $3)",
                array(
                    $loginDataEntity->getUserId(),
                    $loginDataEntity->getEmail(),
                    $loginDataEntity->getPassword()
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
            $loginData['password']
        );
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
