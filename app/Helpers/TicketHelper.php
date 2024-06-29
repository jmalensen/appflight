<?php
namespace App\Helpers;

use App\Models\Ticket;

class TicketHelper
{
    /**
     * Generate a fake airplane ticket number.
     *
     * @return string
     */
    public static function generateFakeTicketNumber(){

        // Define possible letters and numbers
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';

        do {
            // Generate 3 random letters
            $randomLetters = substr(str_shuffle($letters), 0, 3);

            // Generate 6 random numbers
            $randomNumbers = substr(str_shuffle($numbers), 0, 6);

            // Combine letters and numbers to form the ticket number
            $ticketNumber = $randomLetters . $randomNumbers;

            // Check if the ticket number already exists in the database
            $exists = Ticket::where('id', $ticketNumber)->exists();
        } while ($exists);

        return $ticketNumber;
    }
}