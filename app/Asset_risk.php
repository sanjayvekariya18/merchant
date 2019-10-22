<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Asset_risk.
 *
 * @author  The scaffold-interface created at 2018-02-17 04:20:55pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Asset_risk extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'risk_id';
    protected $table = 'asset_risk';

	
}
