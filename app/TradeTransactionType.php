<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TradeTransactionType.
 *
 * @author  The scaffold-interface created at 2018-02-06 01:54:39pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class TradeTransactionType extends Model
{
	
    public $timestamps = false;
    protected $primaryKey = 'trade_transaction_type_id';
    protected $table = 'trade_transaction_type';

}
