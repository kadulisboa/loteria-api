<?php

namespace app;

use App\Core\Factory;
use App\Core\Input;
use App\Core\Router;
use PHPUnit\Framework\TestCase;

class RoutesTest extends TestCase
{
    private $pdo;
    private $router;

    protected function setUp(): void
    {
        // Configura o SQLite em memória para os testes
        $this->pdo = Factory::createDatabaseConnection('sqlite');
        $sql1 = "
            CREATE TABLE IF NOT EXISTS draw (
                id SERIAL PRIMARY KEY,
                won_ticket VARCHAR(50),
                drew_at TIMESTAMP,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
        $sql2 = "CREATE TABLE IF NOT EXISTS tickets (
                id SERIAL PRIMARY KEY,
                tripulante_id INT NOT NULL,
                ticket VARCHAR(50) NOT NULL,
                draw_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT fk_draw FOREIGN KEY (draw_id) REFERENCES draw(id) ON DELETE CASCADE
            )";
        $sql3 = "CREATE TABLE IF NOT EXISTS winners (
                id SERIAL PRIMARY KEY,
                draw_id INT NOT NULL,
                ticket_id INT NOT NULL,
                CONSTRAINT fk_draw FOREIGN KEY (draw_id) REFERENCES draw(id) ON DELETE CASCADE,
                CONSTRAINT fk_ticket FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE
            )";

        $this->pdo->query($sql1);
        $this->pdo->query($sql2);
        $this->pdo->query($sql3);
        $this->router = new Router();

        $router = $this->router;
        $pdo = $this->pdo;
        include __DIR__.'/../../app/routes.php';
    }

    public function testCreateDraw()
    {
        // Simula uma requisição POST para criar um sorteio
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/sorteio/criar';

        ob_start();
        $this->router->resolve();
        $output = ob_get_clean();
        $expectedOutput = json_encode(['DrawId' => '1']);

        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM draw");
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        $this->assertSame($expectedOutput, $output);
        $this->assertEquals(1, $result['count']);
    }

    public function testCreateTickets()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/bilhete/criar';

        $postData = json_encode([
            'quantity' => 1,
            'tens' => 6,
            'drawId' => 1,
            'tripulante_id' => 123
        ]);

        $inputMock = $this->createMock(Input::class);
        $inputMock->method('get')->willReturn($postData);

        Factory::setInstance($inputMock);

        ob_start();
        $this->router->resolve();
        $output = ob_get_clean();

        // Decodifica o JSON de saída
        $response = json_decode($output, true);

        $this->assertSame(6, count($response['tickets'][0]));
        $this->assertSame(201, http_response_code());
    }

}