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
class Wallet_confirm_list extends Model
{
	
    public $timestamps = false;
    protected $primaryKey = 'list_id';
    protected $table = 'wallet_confirm_list';

}
