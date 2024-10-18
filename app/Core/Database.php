<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private $connection;

    public function __construct(string $dsn, ?string $username, ?string $password)
    {
        try {
            if ($dsn === 'sqlite::memory:') {
                $this->connection = new PDO($dsn);
            } else {
                $this->connection = new PDO($dsn, $username, $password);
            }
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new \Exception("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }
}