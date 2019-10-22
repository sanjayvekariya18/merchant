<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
/**
 * Class Hase_staff.
 *
 * @author  The scaffold-interface created at 2017-03-08 07:43:26am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Portal_password extends Authenticatable
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'user_id';
    protected $table = 'portal_password';

	public function passwordSecurity()
    {
        return $this->hasOne('App\PasswordSecurity','user_id','user_id');
    }
}
