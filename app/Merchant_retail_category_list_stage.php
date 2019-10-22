<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_merchant_retail_category_list.
 *
 * @author  The scaffold-interface created at 2017-03-29 02:15:52pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Merchant_retail_category_list_stage extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'category_list_id';
    protected $table = 'merchant_retail_category_list_stage';

	
}
