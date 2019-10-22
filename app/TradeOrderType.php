<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TradeOrderType.
 *
 * @author  The scaffold-interface created at 2018-02-06 01:54:39pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class TradeOrderType extends Model
{
	
    public $timestamps = false;    
    protected $table = 'trade_order_type';
    protected $primaryKey = 'type_id';

}
