<?php

class ListEntity
{
    private $id;
    private $boardId;
    private $name;
    private $position;
    private $createdBy;
    private $createdAt;

    public function __construct($id, $boardId, $name, $position, $createdBy, $createdAt)
    {
        $this->id = $id;
        $this->boardId = $boardId;
        $this->name = $name;
        $this->position = $position;
        $this->createdBy = $createdBy;
        $this->createdAt = $createdAt;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getBoardId()
    {
        return $this->boardId;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}

