<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Asset_sale_list.
 *
 * @author  The scaffold-interface created at 2018-02-25 05:27:57pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Asset_sale_list extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'list_id';
    protected $table = 'asset_sale_list';

	
}
