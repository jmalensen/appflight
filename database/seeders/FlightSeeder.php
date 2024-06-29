<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Flight;

class FlightSeeder extends Seeder
{
    /**
     * Method to generate a random future date
     *
     * @return \Carbon\Carbon
     */
    private function generateRandomFutureDate() {
        // Define the range for the future date
        $daysToAdd = rand(1, 365); // Random number of days between 1 and 365

        $hoursToAdd = rand(1, 24); // Random number of hours between 1 and 24
    
        // Create a random future date
        $randomFutureDate = \Carbon\Carbon::now()->addDays($daysToAdd)->addHours($hoursToAdd);
    
        return $randomFutureDate;
    }


    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $flights = [
            [
                'id' => 1,
                'departure_time' => $this->generateRandomFutureDate(),
                'source_airport' => 'Arlanda',
                'destination_airport' => 'Berlin',
            ],
            [
                'id' => 2,
                'departure_time' => $this->generateRandomFutureDate(),
                'source_airport' => 'Paris',
                'destination_airport' => 'Milan',
            ],
            [
                'id' => 3,
                'departure_time' => $this->generateRandomFutureDate(),
                'source_airport' => 'Arlanda',
                'destination_airport' => 'Milan',
            ],
            [
                'id' => 4,
                'departure_time' => $this->generateRandomFutureDate(),
                'source_airport' => 'Göteborg',
                'destination_airport' => 'Dublin',
            ],
            [
                'id' => 5,
                'departure_time' => $this->generateRandomFutureDate(),
                'source_airport' => 'London',
                'destination_airport' => 'Bruxelles',
            ],
            [
                'id' => 6,
                'departure_time' => $this->generateRandomFutureDate(),
                'source_airport' => 'Copenhag',
                'destination_airport' => 'Madrid',
            ],
            [
                'id' => 7,
                'departure_time' => $this->generateRandomFutureDate(),
                'source_airport' => 'Paris',
                'destination_airport' => 'Berlin',
            ],
            [
                'id' => 8,
                'departure_time' => $this->generateRandomFutureDate(),
                'source_airport' => 'Milan',
                'destination_airport' => 'Dublin',
            ],
            
        ];

        // Create flights items
        $this->createFlights($flights);
    }

    private function createFlights($flights)
    {
        foreach ($flights as $flight) {
            Flight::create([
                'id' => $flight['id'],
                'departure_time' => $flight['departure_time'],
                'source_airport' => $flight['source_airport'],
                'destination_airport' => $flight['destination_airport'],
            ]);
        }
    }
}
