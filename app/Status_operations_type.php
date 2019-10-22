<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Status_operations_type.
 *
 * @author  The scaffold-interface created at 2018-02-14 08:06:23pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Status_operations_type extends Model
{
	
    public $timestamps = false;
    protected $primaryKey = 'type_id';
    protected $table = 'status_operations_type';

	
}
