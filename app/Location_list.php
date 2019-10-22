<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Merchant_city_list.
 *
 */
class Location_list extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'list_id';
    protected $table = 'location_list';
}
