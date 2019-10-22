<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Transaction_summary.
 *
 * @author  The scaffold-interface created at 2018-02-06 01:57:57pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Transaction_summary extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'summary_id';
    protected $table = 'transactions_summary';
    protected $fillable = ['order_id'];

	
}
