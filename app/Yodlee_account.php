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
class Yodlee_account extends Model
{
	
    public $timestamps = false;
    protected $primaryKey = 'account_id';
    protected $table = 'yodlee_account';
    protected $fillable = ['id', 'yodlee_login_id'];

}
