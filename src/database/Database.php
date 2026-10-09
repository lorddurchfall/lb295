<?php

namespace App\database;

use mysqli;

class Database
{
    private mysqli $connection;

    /**
     * Opens a connection to the local XAMPP MySQL database.
     */
    public function __construct()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $username = getenv('DB_USERNAME') ?: 'root';
        $password = getenv('DB_PASSWORD') ?: '';
        $database = getenv('DB_DATABASE') ?: 'lb295';
        $port = (int) (getenv('DB_PORT') ?: 3306);

        $this->connection = new mysqli(
            $host,
            $username,
            $password,
            $database,
            $port
        );

        $this->connection->set_charset('utf8mb4');
    }

    /**
     * Returns the active database connection.
     */
    public function getConnection(): mysqli
    {
        return $this->connection;
    }
}