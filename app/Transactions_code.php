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
class Transactions_code extends Model
{
	
    public $timestamps = false;
    protected $primaryKey = 'code_id';
    protected $table = 'transactions_code';

	
}
