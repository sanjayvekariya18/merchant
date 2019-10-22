<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_reservations_alert.
 */
class Reservations_alert extends Model
{
    protected $table = 'reservations_alert';

	protected $primaryKey = 'alert_id';

    public $timestamps = false;
}
