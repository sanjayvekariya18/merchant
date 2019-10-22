<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_merchant_retail_category_option.
 *
 */
class Merchant_retail_category_option extends Model
{
    public $timestamps = false;
	protected $primaryKey = 'category_option_type_id';
    protected $table = 'merchant_retail_category_option';
}
