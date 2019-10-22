<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_activity.
 *
 * @author  The scaffold-interface created at 2017-03-19 06:03:27am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Activity extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'activity_id';
    protected $table = 'activities';

	
}
