<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_staff.
 *
 * @author  The scaffold-interface created at 2017-03-08 07:43:26am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Merchant_type extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'merchant_type_id';
    protected $table = 'merchant_type';

	
}
