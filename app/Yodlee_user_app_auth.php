<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Account.
 *
 * @author  The scaffold-interface created at 2018-02-06 01:54:39pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Yodlee_user_app_auth extends Model
{
	
    public $timestamps = false;
    protected $primaryKey = 'auth_id';
    protected $table = 'yodlee_user_app_auth';

}
