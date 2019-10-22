<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Exchange_rate.
 *
 * @author  The scaffold-interface created at 2018-02-13 07:20:28pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Exchange_rate extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'rate_id';
    protected $table = 'exchange_rates';

	
}
