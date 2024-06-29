<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Ticket
 *
 * @property int $id
 * @property string|null $ticket_number
 * @property int|null $flight_id
 * @property int|null $customer_id
 * @property string|null $seat
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket whereId($value)
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket whereTicketNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket whereFlightId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket whereSeat($value)
 */
class Ticket extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'ticket_number',
        'flight_id',
        'customer_id',
        'seat'
    ];

    public static $rules = [
		'ticket_number' => ['string', 'max:40', 'required'],
		'flight_id' => ['integer', 'min:0', 'required'],
		'customer_id' => ['integer', 'min:0', 'required'],
		'seat' => ['string', 'max:10', 'required'],
	];

    /**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
    protected $casts = [
		'ticket_number' => 'string',
		'flight_id' => 'integer',
		'customer_id' => 'integer',
		'seat' => 'string',
	];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];


    /********************************************************************/
    /**************************** RELATIONS *****************************/
    /********************************************************************/

    /**
     * Get flight of the ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function flight(){
        return $this->belongsTo(Flight::class);
    }

    /**
     * Get customer of the ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function customer(){
        return $this->belongsTo(Customer::class);
    }
}
