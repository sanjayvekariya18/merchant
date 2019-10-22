<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Merchant_city_list.
 *
 * @author  The scaffold-interface created at 2018-02-06 01:54:39pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Merchant_city_list extends Model
{
	
    public $timestamps = false;
    protected $primaryKey = 'list_id';
    protected $table = 'merchant_city_list';

}
