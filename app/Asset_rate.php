<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Asset_rate.
 *
 * @author  The scaffold-interface created at 2018-02-15 07:57:38pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Asset_rate extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'rate_id';
    protected $table = 'asset_rates';

	
}
