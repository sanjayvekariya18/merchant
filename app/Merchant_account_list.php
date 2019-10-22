<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TradeSideType.
 *
 * @author  The scaffold-interface created at 2017-03-19 06:03:27am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Merchant_account_list  extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'list_id';
    protected $table = 'merchant_account_list';

	
}
