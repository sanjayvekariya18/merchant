<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Account_type.
 *
 * @author  The scaffold-interface created at 2018-02-18 01:01:04pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Account_type extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'type_id';
    protected $table = 'account_type';

	
}
