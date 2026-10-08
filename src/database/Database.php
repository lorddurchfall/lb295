<?php

namespace App\database;

use mysqli;

class Database
{
    private mysqli $connection;

    /**
     * Creates a connection to the MySQL database.
     */
    public function __construct()
    {
        $this->connection = new mysqli(
            $_ENV['DB_HOST'],
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD'],
            $_ENV['DB_DATABASE'],
            (int) $_ENV['DB_PORT']
        );

        if ($this->connection->connect_error) {
            die('Database connection failed');
        }
    }

    /**
     * Returns the active mysqli connection.
     */
    public function getConnection(): mysqli
    {
        return $this->connection;
    }
}