<?php

namespace App\Controllers;

use App\Core\Factory;
use App\Core\HttpException;
use App\Core\Response;
use App\Models\Ticket;
use App\Models\Draw;
use App\Repositories\TicketRepository;
use App\Repositories\DrawRepository;
use App\Core\Input;
use function PHPUnit\Framework\throwException;

class TicketController
{
    private $ticketModel;

    public function __construct(Ticket $ticketModel)
    {
        $this->ticketModel = $ticketModel;
    }

    /**
     * @throws HttpException
     * @throws \JsonException
     */
    public function generateTickets()
    {
        $input = Factory::createInput();
        $inputData = $input->get();
        $params = json_decode($inputData, true, 512, JSON_THROW_ON_ERROR);

        $quantity = $params['quantity'];
        $tens = $params['tens'];
        $drawId = $params['drawId'];
        $tripulanteId = $params['tripulante_id'];

        $tickets = $this->ticketModel->generateTickets($quantity, $tens, $tripulanteId, $drawId);
        $this->ticketModel->saveTickets($tripulanteId, $drawId);

        Response::Json(["tickets" => $tickets], 201);
    }
}
