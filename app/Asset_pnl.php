<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Asset_pnl.
 *
 * @author  The scaffold-interface created at 2018-02-17 04:20:55pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Asset_pnl extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'pnl_id';
    protected $table = 'asset_pnl';

	
}
