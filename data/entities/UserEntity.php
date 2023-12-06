<?php

class UserEntity
{
    private $id;
    private $nickname;
    private $email;
    private $firstName;
    private $lastName;
    private $createdAt;


    public function __construct($id, $nickname, $email, $firstName, $lastName, $createdAt)
    {
        $this->id = $id;
        $this->nickname = $nickname;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->createdAt = $createdAt;
    }


    public function getId()
    {
        return $this->id;
    }

    public function getNickname()
    {
        return $this->nickname;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}

