<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_countries.
 *
 */
class Countries extends Model
{
    protected $table = 'location_country';

	protected $primaryKey = 'country_id';

    public $timestamps = false;
}
