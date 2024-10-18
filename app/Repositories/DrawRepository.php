<?php

namespace App\Repositories;

class DrawRepository
{
    private $pdo;
    private const TABLE = 'draw';

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function createDraw()
    {
        $sql = "INSERT INTO ". self::TABLE ." DEFAULT VALUES";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        if ($this->pdo->getAttribute(\PDO::ATTR_DRIVER_NAME) !== 'pgsql') {
            return $this->pdo->lastInsertId();
        }

        return $this->pdo->query("SELECT LASTVAL()")->fetchColumn();
    }

    public function getDraw($filters = null, $others = null)
    {
        $sql = "SELECT * from ". self::TABLE;
        if ($filters) {
            $sql .= " WHERE ".$filters;
        }

        if ($others) {
            $sql .= " ".$others;
        }

        return $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function updateDraw($id, $data)
    {
        $setPart = [];
        foreach ($data as $column => $value) {
            $setPart[] = "$column = :$column";
        }
        $setPart = implode(', ', $setPart);

        $sql = "UPDATE draw SET $setPart WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        $data['id'] = $id;

        return $stmt->execute($data);
    }

    public function getDrawWinner($id)
    {
        $sql = "SELECT * from winners WHERE draw_id = ".$id;
        return $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function saveWinners(array $tickets)
    {
        // Monta a query com placeholders
        $sql = 'INSERT INTO winners (draw_id, ticket_id) VALUES ';
        $params = [];

        foreach ($tickets as $index => $ticket) {
            $sql .= "(:draw_id{$index}, :ticket_id{$index}),";

            // Define os valores para os placeholders
            $params["draw_id{$index}"] = $ticket['draw_id'];
            $params["ticket_id{$index}"] = $ticket['id'];
        }

        // Remove a última vírgula da query
        $sql = rtrim($sql, ',');

        // Prepara e executa a query com os valores
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}
