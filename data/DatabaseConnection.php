<?php

class DatabaseConnection
{
    private static $instance;
    private $connection;

    private function __construct()
    {
        $this->connection = pg_connect(
            "host=localhost dbname=kanban user=postgres password=3028Rh332"
        );
        if (!$this->connection) {
            die("Connection failed: " . pg_last_error());
        }
    }

    public static function getInstance(): DatabaseConnection
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function __destruct()
    {
        if ($this->connection) {
            pg_close($this->connection);
        }
    }
}
