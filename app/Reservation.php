<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_reservation.
 */
class Reservation extends Model
{
    protected $table = 'reservations';

	protected $primaryKey = 'reservation_id';

    public $timestamps = false;
}
