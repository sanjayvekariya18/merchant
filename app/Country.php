<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_country.
 *
 * @author  The scaffold-interface created at 2017-05-06 11:37:37am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Country extends Model
{
	
	
	protected $primaryKey = 'country_id';

    public $timestamps = false;
    
    protected $table = 'location_country';

	
}
