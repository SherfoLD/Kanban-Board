<?php

class CardEntity
{
    private $id;
    private $listId;
    private $name;
    private $position;
    private $createdBy;
    private $createdAt;

    public function __construct($id, $listId, $name, $position, $createdBy, $createdAt)
    {
        $this->id = $id;
        $this->listId = $listId;
        $this->name = $name;
        $this->position = $position;
        $this->createdBy = $createdBy;
        $this->createdAt = $createdAt;
    }

    // Getter methods
    public function getId()
    {
        return $this->id;
    }

    public function getListId()
    {
        return $this->listId;
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
?>
