<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_status_history.
 *
 */
class Status_history extends Model
{
    protected $table = 'status_history';

	protected $primaryKey = 'status_history_id';

    public $timestamps = false;
}
