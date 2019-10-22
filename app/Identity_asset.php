<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_merchant.
 *
 * @author  The scaffold-interface created at 2017-03-11 03:14:10am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Identity_asset extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'identity_id';
    protected $table = 'identity_asset';
	
}
