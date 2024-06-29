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


    /**
     * Method to generate the seat number
     *
     * @param int $flightId
     * @return array ['status' => '', 'result' => '']
     */
    private function generateSeat($flightId){

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
            
        } while (Ticket::where('flight_id', $flightId)->where('seat', $seat)->exists());

        return [
            'status' => 'success',
            'result' => $seat
        ];
    }

}
