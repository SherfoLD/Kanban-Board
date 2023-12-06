<?php

class UserRepository
{
    public function save(UserEntity $userEntity)
    {
        if ($userEntity->getId() != null) {
            return pg_query_params(
                self::getConnection(),
                "UPDATE user SET nickname = $1, email = $2, first_name = $3, last_name = $4, created_at = $5 WHERE id = $6",
                array(
                    $userEntity->getNickname(),
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
                "INSERT INTO user(nickname, email, first_name, last_name, created_at) VALUES ($1, $2, $3, $4, $5)",
                array(
                    $userEntity->getNickname(),
                    $userEntity->getEmail(),
                    $userEntity->getFirstName(),
                    $userEntity->getLastName(),
                    $userEntity->getCreatedAt()
                )
            );
        }
    }

    public function findById($id)
    {
        $result = pg_query_params(
            self::getConnection(),
            "SELECT nickname, email, first_name, last_name, created_at FROM user WHERE id = $1",
            array($id)
        );
        $userData = pg_fetch_assoc($result);

        return new UserEntity(
            $id,
            $userData['nickname'],
            $userData['email'],
            $userData['first_name'],
            $userData['last_name'],
            $userData['created_at']
        );
    }

    public function deleteById($id)
    {
        return pg_query_params(
            self::getConnection(),
            "DELETE FROM user WHERE id = $1",
            array($id)
        );
    }

    public static function getConnection()
    {
        return DatabaseConnection::getInstance()->getConnection();
    }
}
