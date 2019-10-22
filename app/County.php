<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class County.
 */
class County extends Model
{
    protected $table = 'location_county';

	protected $primaryKey = 'county_id';

    public $timestamps = false;
}
