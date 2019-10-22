<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_customer.
 *
 * @author  The scaffold-interface created at 2017-03-02 04:01:26am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Customer extends Model
{
	
	
    protected $table = 'customers';

    protected $primaryKey = 'customer_id';

    public $timestamps = false;
	
}
