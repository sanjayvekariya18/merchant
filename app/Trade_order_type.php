<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Trade_order_type.
 *
 * @author  The scaffold-interface created at 2018-02-18 06:26:29pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Trade_order_type extends Model
{
	
	
    public $timestamps = false;    
    protected $table = 'trade_order_type';
    protected $primaryKey = 'type_id';

	
}
