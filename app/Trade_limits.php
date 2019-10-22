<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Trade_limits.
 *
 * @author  The scaffold-interface created at 2018-02-10 04:57:00pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Trade_limits extends Model
{
	
	
    public $timestamps = false;    
    protected $table = 'trade_limits';
    protected $primaryKey = 'limits_id';
	
}
