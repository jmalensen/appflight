<?php

namespace App\Http\Controllers;

use App\Helpers\TicketHelper;
use App\Http\Requests\CreateTicketRequest;
use App\Models\Customer;
use App\Models\Ticket;
use App\Models\Flight;
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
        // Find the flight id
        $flight = Flight::find($request->flight_id);

        // Find the customer with the passport_id or create it
        $customer = Customer::firstOrCreate(['passport_id' => $request->passport_number], ['name' => $request->name]);
        
        $seatResult = $this->generateSeat($flight->id);
        // If no more seat are available
        if($seatResult['status'] == 'error'){
            return response()->json(['message' => $seatResult['result']], 503);
        }
        $ticketNumber = TicketHelper::generateFakeTicketNumber();

        $ticket = Ticket::create([
            'ticket_number' => $ticketNumber,
            'flight_id' => $flight->id,
            'customer_id' => $customer->id,
            'seat' => $seatResult['result'],
        ]);

        return response()->json(['message' => 'Ticket created successfully', 'ticket' => $ticket], 201);
    }


    /**
     * Method to cancel the ticket
     *
     * @param string $ticketNumber
     * @return Response
     */
    public function cancel($ticketNumber){
        $ticket = Ticket::whereTicketNumber($ticketNumber)->first();

        // If no ticket found
        if (empty($ticket)) {
            return response()->json(['message' => 'Ticket not found'], 404);
        }

        $ticket->delete();

        return response()->json(['message' => 'Ticket cancelled successfully'], 200);
    }


    /**
     * Method to update the seat of the ticket
     *
     * @param Request $request
     * @param string $ticketNumber
     * @return Response
     */
    public function updateSeat(Request $request, $ticketNumber){
        $ticket = Ticket::whereTicketNumber($ticketNumber)->first();

        // If no ticket found
        if (empty($ticket)) {
            return response()->json(['message' => 'Ticket not found'], 404);
        }

        $newSeatResult = $this->generateSeat($ticket->flight_id, $ticket->seat);

        // If no more seat are available
        if($newSeatResult['status'] == 'error'){
            return response()->json(['message' => $newSeatResult['result'].', your seat remained the same: '.$ticket->seat], 503);
        }

        $ticket->seat = $newSeatResult['result'];
        $ticket->save();

        return response()->json(['message' => 'Seat updated successfully', 'ticket' => $ticket], 200);
    }


    /**
     * Method to generate the seat number
     *
     * @param int $flightId
     * @param string $currentSeat
     * @return array ['status' => '', 'result' => '']
     */
    private function generateSeat($flightId, $currentSeat = null){

        $rows = range('A', 'D');
        $numbers = range(1, 32);

        // Total possible seats (4 rows * 32 seats each = 128)
        $totalSeats = count($rows) * count($numbers);

        // Get the number of booked seats for the flight
        $bookedSeatsCount = Ticket::where('flight_id', $flightId)->count();

        // Check if all seats are booked
        if ($bookedSeatsCount >= $totalSeats) {
            return [
                'status' => 'error',
                'result' => 'No available seats'
            ];
        }

        do {
            $seat = $rows[array_rand($rows)] . $numbers[array_rand($numbers)];
            
        } while (($currentSeat && $seat == $currentSeat) || Ticket::where('flight_id', $flightId)->where('seat', $seat)->exists());

        return [
            'status' => 'success',
            'result' => $seat
        ];
    }

}
