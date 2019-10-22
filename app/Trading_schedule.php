<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Trading_schedule.
 *
 * @author  The scaffold-interface created at 2018-02-14 05:38:42pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Trading_schedule extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'trading_schedule_id';
    protected $table = 'trading_schedule';
	

	
}
