<?php

namespace App\Models;

use App\Core\HttpException;
use App\Repositories\TicketRepository;

class Ticket
{
    private $tickets;
    private $ticketRepository;

    public function __construct(TicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * @throws HttpException
     */
    public function generateTickets($quantity, $amountOfTens, $tripulanteId, $drawId): array
    {
        $numbers = [];

        if ($quantity < 1 || $quantity > 50 || $amountOfTens < 6 || $amountOfTens > 10) {
            throw new HttpException(400, 'Invalid data');
        }

        if(count($this->getTicketsByDrawAndTripulante($drawId, $tripulanteId)) === 50) {
            throw new HttpException(400, 'Max tickets created');
        }

        for($i = 1; $i <= $quantity; $i++){
            $numbers[] = $this->generateNumbers($amountOfTens);
        }

        $this->tickets = $numbers;

        return $numbers;
    }

    public function generateWinningTicket()
    {

        $winningTicket = $this->generateNumbers(6);
        $this->tickets = $winningTicket;
        return $winningTicket;
    }
    private function generateNumbers(int $amountOfTens)
    {
        if ($amountOfTens < 6 || $amountOfTens > 10) {
            throw new \InvalidArgumentException("A quantidade de dezenas deve ser entre 6 e 10.", 400);
        }

        $numbers = range(1, 60);
        shuffle($numbers);
        $tens = array_slice($numbers, 0, $amountOfTens);
        sort($tens);
        return $tens;
    }

    public function getTickets()
    {
        return $this->tickets;
    }

    public function getTicketsByDraw($drawId)
    {
        return $this->ticketRepository->getTicketsByDraw($drawId);
    }

    public function getTicketsByDrawAndTripulante($drawId, $tripulanteId)
    {
        return $this->ticketRepository->getTicketsByDrawAndTripulante($tripulanteId, $drawId);
    }

    public function saveTickets($tripulanteId, $drawId) {
        $this->ticketRepository->saveTickets($this->getTickets(), $tripulanteId, $drawId);
    }
}
