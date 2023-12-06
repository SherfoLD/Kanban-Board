<?php

class TeamUserEntity
{
    private $id;
    private $userId;
    private $teamId;
    private $role;

    public function __construct($id, $userId, $teamId, $role)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->teamId = $teamId;
        $this->role = $role;
    }

    // Getter methods
    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getTeamId()
    {
        return $this->teamId;
    }

    public function getRole()
    {
        return $this->role;
    }
}