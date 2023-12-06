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

    // Public method to get the singleton instance
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Public method to get the PostgreSQL connection
    public function getConnection()
    {
        return $this->connection;
    }

    // Private clone method to prevent cloning of the instance
    private function __clone()
    {
    }

    // Private unserialize method to prevent unserializing of the instance
    private function __wakeup()
    {
    }

    // Destructor to close the connection when the object is destroyed
    public function __destruct()
    {
        if ($this->connection) {
            pg_close($this->connection);
        }
    }
}
