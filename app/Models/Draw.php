<?php

namespace App\Models;

use App\Core\HttpException;
use App\Repositories\DrawRepository;
use DateTime;

class Draw
{
    private $ticketModel;
    private $drawRepository;

    public function __construct(DrawRepository $drawRepository, Ticket $ticketModel)
    {
        $this->drawRepository = $drawRepository;
        $this->ticketModel = $ticketModel;
    }

    public function createDraw()
    {
        return $this->drawRepository->createDraw();
    }

    /**
     * @throws HttpException
     */
    public function createWinningTicket($drawId)
    {
        if(!$this->drawExist($drawId)) throw new HttpException(404, "Draw not found");
        if($this->drawHasWinner($drawId)) throw new HttpException(400, "Draw already has winner");

        $winningTicket = $this->ticketModel->generateWinningTicket();
        $this->drawRepository->updateDraw($drawId, ['won_ticket' => implode(',', $winningTicket), 'drew_at' => (new DateTime())->format("Y-m-d H:m:s.ms")]);
        return $winningTicket;
    }

    /**
     * @throws HttpException
     */
    public function getResult($drawId)
    {
        $winningTicket = $this->createWinningTicket($drawId);
        $ticketsByDraw = $this->ticketModel->getTicketsByDraw($drawId);

        $wons = [];

        foreach ($ticketsByDraw as $ticket) {
            $tensTicket = explode(',', $ticket['ticket']);

            if (count(array_intersect($tensTicket, $winningTicket)) >= 6) {
                $wons[] = $ticket;
            }

        }
        if(count($wons) > 0) {
            $this->saveWinners($wons);
        }

        return $wons;

    }

    private function drawExist($id)
    {
        return count($this->drawRepository->getDraw("id = ".$id)) > 0;
    }

    private function drawHasWinner($drawId)
    {
        return count($this->drawRepository->getDrawWinner($drawId)) > 0;
    }

    private function saveWinners($winners)
    {
        $this->drawRepository->saveWinners($winners);
    }

    public function getWinningTicket($drawId)
    {
        return $this->drawRepository->getDraw("id = ".$drawId)[0]['won_ticket'];
    }
    public function exibirTabelaResultados($wonTicket, $tickets)
    {
        $html = "<table border='1'><thead><tr><th>Ticket</th><th>Dezenas Sorteadas</th></tr></thead><tbody>";

        foreach ($tickets as $ticket) {

            $acertos = array_intersect(explode(',',$ticket['ticket']), explode(",", $wonTicket));
            $html .= "<tr>";
            $html .= "<td>" . $ticket['ticket'] . "</td>";
            $html .= "<td>" . implode(", ", $acertos) . "</td>";
            $html .= "</tr>";
        }
        $html .= "</tbody></table>";

        return $html;
    }
}
