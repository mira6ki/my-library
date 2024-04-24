<?php

namespace Repository;

class DatabaseConnection
{
    private string $servername = "localhost";

    private string $username = "root";
    private string $password = "password";
    private string $dbname = "books";
    private $conn;


    public function getServerName(): string
    {
        return $this->servername;
    }

    public function getDbUserName(): string
    {
        return $this->username;
    }

    public function getDbPassword(): string
    {
        return $this->password;
    }

    public function getDbName(): string
    {
        return $this->dbname;
    }


    public function __construct()
    {
        $dsn = "pgsql:host={$this->getServerName()};dbname={$this->getDbName()}";
        try {
            $this->conn = new \PDO($dsn, $this->getDbUserName(), $this->getDbPassword());
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function closeConnection(): self
    {
        $this->conn = null;

        return $this;
    }
}
