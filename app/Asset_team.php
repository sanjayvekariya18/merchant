<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Asset_team.
 *
 * @author  The scaffold-interface created at 2018-02-22 05:50:45pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Asset_team extends Model
{
	
	
    public $timestamps = false;
    protected $table = 'asset_team';
    protected $primaryKey = 'team_id';
	
}
