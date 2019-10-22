<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Timezone.
 *
 * @author  The scaffold-interface created at 2018-02-14 05:38:42pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Timezone extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'timezone_id';
    protected $table = 'timezone';
	

	
}
