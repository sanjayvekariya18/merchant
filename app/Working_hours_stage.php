<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_working_hours_stage.
 *
 */
class Working_hours_stage extends Model
{
    protected $table = 'working_hours_stage';

	protected $primaryKey = 'hours_id';

    public $timestamps = false;
}
