<?php

namespace App\Controllers;

use App\Core\Database;

class MigrationController
{

    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function runMigrations()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS draw (
            id SERIAL PRIMARY KEY,
            won_ticket VARCHAR(50),
            drew_at TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS tickets (
            id SERIAL PRIMARY KEY,
            tripulante_id INT NOT NULL,
            ticket VARCHAR(50) NOT NULL,
            draw_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_draw FOREIGN KEY (draw_id) REFERENCES draw(id) ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS winners (
            id SERIAL PRIMARY KEY,
            draw_id INT NOT NULL,
            ticket_id INT NOT NULL,
            CONSTRAINT fk_draw FOREIGN KEY (draw_id) REFERENCES draw(id) ON DELETE CASCADE,
            CONSTRAINT fk_ticket FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE
        );
        ";

        try {
            // Executa o script SQL
            $this->pdo->exec($sql);
            echo json_encode(['message' => 'Tabelas criadas com sucesso!']);
        } catch (\PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
