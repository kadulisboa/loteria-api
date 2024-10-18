<?php

namespace App\Repositories;

class TicketRepository
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function saveTickets(array $tickets, $tripulanteId, $drawId)
    {
        $sql = 'INSERT INTO tickets (tripulante_id, ticket, draw_id) VALUES ';
        $params = [];

        foreach ($tickets as $index => $ticket) {
            $sql .= "(:tripulante_id{$index}, :ticket{$index}, :draw_id{$index}),";

            $params["tripulante_id{$index}"] = $tripulanteId;
            $params["ticket{$index}"] = implode(',', $ticket);
            $params["draw_id{$index}"] = $drawId;
        }

        $sql = rtrim($sql, ',');

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function getTicketsByDrawAndTripulante($tripulanteId, $drawId)
    {
        $sql = 'SELECT * FROM tickets WHERE tripulante_id = :tripulante_id AND draw_id = :draw_id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tripulante_id' => $tripulanteId, 'draw_id' => $drawId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTicketsByDraw($drawId)
    {
        $sql = 'SELECT * FROM tickets WHERE draw_id = :draw_id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['draw_id' => $drawId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
