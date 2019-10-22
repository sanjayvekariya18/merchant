<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Transactions_ledger.
 *
 * @author  The scaffold-interface created at 2018-02-08 04:40:12pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Transaction_ledger extends Model
{
	
    public $timestamps = false;
    protected $primaryKey = 'ledger_id';
    protected $table = 'transactions_ledger';

	
}
