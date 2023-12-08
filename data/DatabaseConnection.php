<?php

use PgSql\Connection;

class DatabaseConnection
{
    private static self|null $instance = null;
    private Connection $connection;

    private function __construct()
    {
        $this->connection = pg_connect(
            "host=localhost dbname=kanban user=postgres password=3028Rh332"
        );
    }

    public static function getInstance(): self
    {
        if (self::$instance === null)
            self::$instance = new self;

        return self::$instance;
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    public function __destruct()
    {
        pg_close($this->connection);
    }
}
