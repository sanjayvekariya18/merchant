<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_permission.
 *
 * @author  The scaffold-interface created at 2017-03-10 04:10:26am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Permission extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'permission_id';
    protected $table = 'permissions';

	
}
