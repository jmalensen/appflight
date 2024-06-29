<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTicketRequest;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Method to create ticket
     *
     * @param CreateTicketRequest $request
     * @return Response
     */
    public function create(CreateTicketRequest $request){

    }


    /**
     * Method to cancel the ticket
     *
     * @param string $ticketNumber
     * @return Response
     */
    public function cancel($ticketNumber){

    }


    /**
     * Method to update the seat of the ticket
     *
     * @param Request $request
     * @param string $ticketNumber
     * @return Response
     */
    public function updateSeat(Request $request, $ticketNumber){

    }

}
