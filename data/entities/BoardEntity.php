<?php

class BoardEntity
{
    private $id;
    private $teamId;
    private $name;
    private $createdAt;

    public function __construct($id, $teamId, $name, $createdAt)
    {
        $this->id = $id;
        $this->teamId = $teamId;
        $this->name = $name;
        $this->createdAt = $createdAt;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTeamId()
    {
        return $this->teamId;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}

