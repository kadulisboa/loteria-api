<?php

namespace App\Controllers;

use App\Core\HttpException;
use App\Core\Response;
use App\Models\Draw;
use App\Models\Ticket;

class DrawController
{
    private $drawModel;
    private $ticketsModel;

    public function __construct(Draw $drawModel, Ticket $ticketsModel)
    {
        $this->drawModel = $drawModel;
        $this->ticketsModel = $ticketsModel;
    }

    public function createDraw()
    {
        $result = $this->drawModel->createDraw();

        !($result > 0) ?
            throw new HttpException(500, 'Error while draw was created')
            :
            Response::json(['DrawId' => $result], 201);

    }

    public function generateDrawResult($id)
    {
        $drawResult = $this->drawModel->getResult($id);
        $show = count($drawResult) ? $drawResult : 'No winners';
        Response::json(['wons' => $show]);

    }

    public function render($id)
    {
        $wonTicket = $this->drawModel->getWinningTicket($id);
        $tickets = $this->ticketsModel->getTicketsByDraw($id);
        echo $this->drawModel->exibirTabelaResultados($wonTicket, $tickets);
    }
}
