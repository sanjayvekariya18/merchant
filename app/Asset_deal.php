<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Asset_deal.
 *
 * @author  The scaffold-interface created at 2018-02-15 07:11:38pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Asset_deal extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'deal_id';
    protected $table = 'asset_deal';

	
}
