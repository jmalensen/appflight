<?php
namespace App\Http\Requests;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;

class CreateTicketRequest extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'flight_id' => 'required|exists:flights,id',
            'passport_number' => Customer::$rules['passport_id'],
            'name' => Customer::$rules['name'],
        ];
    }
}