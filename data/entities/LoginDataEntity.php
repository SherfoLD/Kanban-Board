<?php

class LoginDataEntity
{
    private $id;
    private $userId;
    private $email;
    private $password;
    private $blocked;

    public function __construct($id, $userId, $email, $password, $blocked)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->email = $email;
        $this->password = $password;
        $this->blocked = $blocked;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getBlocked()
    {
        return $this->blocked;
    }
}

