<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PrimitiveHistory.
 *
 * @author  The scaffold-interface created at 2018-02-27 02:24:29pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class PrimitiveHistory extends Model
{
	
	
    protected $table = 'primitive_history';
    public $timestamps = false;
    protected $primaryKey = 'primitive_history_id';
	
}
