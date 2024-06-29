<?php

namespace App\Http\Controllers;

use App\Models\Flight;

class FlightController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){

        $flights = Flight::select('id', 'departure_time', 'source_airport', 'destination_airport')->get()->map(function($flight) {
            return [
                'id' => $flight->id,
                'departure_time' => $flight->formatted_departure_time,
                'source_airport' => $flight->source_airport,
                'destination_airport' => $flight->destination_airport,
            ];
        });

        return response()->json(['message' => 'Flights list retrieved successfully', 'flights' => $flights], 201);
    }

}
