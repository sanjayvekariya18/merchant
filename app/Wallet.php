<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Website_domain.
 *
 * @author  The scaffold-interface created at 2018-03-05 02:19:27pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Wallet extends Model
{
	
	
    protected $table = 'wallet';
    public $timestamps = false;
    protected $primaryKey = 'wallet_id';


	
}
