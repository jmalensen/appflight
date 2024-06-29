<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Customer
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $passport_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereId($value)
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer wherePassportId($value)
 */
class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'passport_id',
        'name'
    ];

    public static $rules = [
		'passport_id' => ['string', 'max:20', 'required'],
        'name' => ['string', 'max:255', 'required'],
	];

    /**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
    protected $casts = [
		'passport_id' => 'string',
		'name' => 'string',
	];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];


    /********************************************************************/
    /**************************** RELATIONS *****************************/
    /********************************************************************/

    /**
     * Get tickets of the customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function tickets(){
        return $this->hasMany(Ticket::class);
    }
}
