<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TradeStatusType.
 *
 * @author  The scaffold-interface created at 2018-02-06 01:54:39pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class TradeStatusType extends Model
{
	
    public $timestamps = false;
    protected $primaryKey = 'trade_status_id';
    protected $table = 'trade_status_type';

}
