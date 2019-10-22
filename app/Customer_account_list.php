<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Customer_account_list.
 *
 * @author  The scaffold-interface created at 2018-02-25 08:41:05pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Customer_account_list extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'list_id';
    protected $table = 'customer_account_list';

	
}
