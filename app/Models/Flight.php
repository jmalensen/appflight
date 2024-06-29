<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Flight
 *
 * @property int $id
 * @property string|null $source_airport
 * @property string|null $destination_airport
 * @property \Illuminate\Support\Carbon|null $departure_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Flight newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Flight newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Flight query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Flight whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Flight whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Flight whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Flight whereId($value)
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Flight whereSourceAirport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Flight whereDestinationAirport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Flight whereDepartureTime($value)
 */
class Flight extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'departure_time',
        'source_airport',
        'destination_airport'
    ];

    public static $rules = [
		'departure_time' => ['required', 'date'],
        'source_airport' => ['string', 'max:255', 'required'],
        'destination_airport' => ['string', 'max:255', 'required'],
	];

    /**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
    protected $casts = [
		'departure_time' => 'datetime',
		'source_airport' => 'string',
		'destination_airport' => 'string',
	];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $appends = ['formatted_departure_time'];


    /**
     * Method to get departure time in the following format: Y-m-d H:i
     *
     * @return Carbon
     */
    public function getFormattedDepartureTimeAttribute(){
        return Carbon::parse($this->departure_time)->format('Y-m-d H:i');
    }


    /********************************************************************/
    /**************************** RELATIONS *****************************/
    /********************************************************************/

    /**
     * Get tickets of the flight
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function tickets() {
        return $this->hasMany(Ticket::class);
    }
}
