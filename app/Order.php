<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_order.
 *
 * @author  The scaffold-interface created at 2017-03-08 06:54:55am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Order extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'order_id';
    protected $table = 'orders';



	
}
