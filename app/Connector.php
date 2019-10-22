<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Connector.
 *
 * @author  The scaffold-interface created at 2018-02-25 08:41:05pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Connector extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'connector_id';
    protected $table = 'connector';

	
}
