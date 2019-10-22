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
class Order_menu extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'order_menu_id';
    protected $table = 'Hase_order_menus';



	
}
