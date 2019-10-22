<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Account.
 *
 * @author  The scaffold-interface created at 2018-02-06 01:54:39pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Trade_order_side_type extends Model
{
	
    public $timestamps = false;
    protected $primaryKey = 'side_type_id';
    protected $table = 'trade_order_side_type';

}
