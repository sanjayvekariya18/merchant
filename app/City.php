<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_city.
 */
class City extends Model
{
    protected $table = 'location_city';

	protected $primaryKey = 'city_id';

    public $timestamps = false;
}
