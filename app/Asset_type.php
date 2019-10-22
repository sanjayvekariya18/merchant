<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Asset.
 *
 * @author  The scaffold-interface created at 2018-02-06 01:54:39pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Asset_type extends Model
{
	
    public $timestamps = false;
    protected $primaryKey = 'asset_type_id';
    protected $table = 'asset_type';

}
