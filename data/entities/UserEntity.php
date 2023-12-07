<?php

class UserEntity
{
    private $id;
    private $email;
    private $firstName;
    private $lastName;
    private $createdAt;


    public function __construct($id, $email, $firstName, $lastName, $createdAt)
    {
        $this->id = $id;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->createdAt = $createdAt;
    }


    public function getId()
    {
        return $this->id;
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

