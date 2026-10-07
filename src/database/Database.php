<?php

namespace App\database;

use mysqli;

class Database
{
    private $connection;

    public function __construct()
    {
        $conn = new mysqli(
            "mysql",
            "slimuser",
            "password",
            "slimdb",
            3306
        );

        if ($conn->connect_error) {
            die("DB Fehler: " . $conn->connect_error);
        }
        $this->connection = $conn;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}


