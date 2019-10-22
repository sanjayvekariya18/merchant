<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_merchant_retail_category_list.
 */
class Merchant_retail_category_list extends Model
{
    public $timestamps = false;
	protected $primaryKey = 'category_list_id';
    protected $table = 'merchant_retail_category_list';	
}
