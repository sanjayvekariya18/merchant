<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_working_hour.
 *
 */
class Working_hour extends Model
{
    protected $table = 'working_hours';

	protected $primaryKey = 'hours_id';

    public $timestamps = false;
}
