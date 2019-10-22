<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Position.
 *
 * @author  The scaffold-interface created at 2018-02-10 04:57:00pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Trade_risk extends Model
{
	
    public $timestamps = false;
    protected $table = 'trade_breach';
    protected $primaryKey = 'breach_id';

}
