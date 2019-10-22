<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_table.
 *
 */
class Table extends Model
{
    protected $table = 'reservations_seating';

	protected $primaryKey = 'seating_id';

    public $timestamps = false;
}
