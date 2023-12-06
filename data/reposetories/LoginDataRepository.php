<?php

class LoginDataRepository
{
    public function save(LoginDataEntity $loginDataEntity)
    {
        if ($loginDataEntity->getId() != null) {
            return pg_query_params(
                self::getConnection(),
                "UPDATE login_data SET user_id = $1, email = $2, password = $3 WHERE id = $4",
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
                "INSERT INTO login_data(user_id, email, password) VALUES ($1, $2, $3)",
                array(
                    $loginDataEntity->getUserId(),
                    $loginDataEntity->getEmail(),
                    $loginDataEntity->getPassword()
                )
            );
        }
    }

    public function findById($id)
    {
        $result = pg_query_params(
            self::getConnection(),
            "SELECT user_id, email, password FROM login_data WHERE id = $1",
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

    public function deleteById($id)
    {
        return pg_query_params(
            self::getConnection(),
            "DELETE FROM login_data WHERE id = $1",
            array($id)
        );
    }

    public static function getConnection()
    {
        return DatabaseConnection::getInstance()->getConnection();
    }
}
