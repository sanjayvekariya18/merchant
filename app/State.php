<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_state.
 */
class State extends Model
{
    protected $table = 'location_state';

	protected $primaryKey = 'state_id';

    public $timestamps = false;
}
