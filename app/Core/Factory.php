<?php

namespace App\Core;

use App\Controllers\TicketController;
use App\Models\Ticket;
use App\Repositories\DrawRepository;
use App\Models\Draw;
use App\Controllers\DrawController;
use App\Repositories\TicketRepository;

class Factory
{
    private static $instance;
    public static function createDrawController($pdo)
    {
        $ticketRepository = new TicketRepository($pdo);
        $ticketModel = new Ticket($ticketRepository);

        $drawRepository = new DrawRepository($pdo);
        $drawModel = new Draw($drawRepository, $ticketModel);
        return new DrawController($drawModel, $ticketModel);
    }

    public static function createTicketController($pdo)
    {
        $ticketRepository = new TicketRepository($pdo);
        $ticketModel = new Ticket($ticketRepository);
        return new TicketController($ticketModel);
    }

    /**
     * @throws \Exception
     */
    public static function createDatabaseConnection($type = 'pgsql'): \PDO
    {
        $dsn = $type === 'pgsql' ? 'pgsql:host=db;port=5432;dbname=loteria' : "sqlite::memory:";
        $username =  $type === 'pgsql' ? 'monetizze' : null;
        $password =  $type === 'pgsql' ? 'strongPassword' : null;

        return (new Database($dsn, $username, $password))->getConnection();
    }

    public static function createInput(): Input
    {
        if (self::$instance) {
            return self::$instance;
        }

        return new Input();
    }

    public static function setInstance($mock)
    {
        self::$instance = $mock;
    }
}

?>