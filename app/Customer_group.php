<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_customer_group.
 *
 * @author  The scaffold-interface created at 2017-02-27 06:11:12am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Customer_group extends Model
{
	
	
    protected $table = 'customer_groups';

    protected $primaryKey = 'customer_group_id';

    public $timestamps = false;
}
