<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class People.
 *
 * @author  The scaffold-interface created at 2018-02-10 04:57:00pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class People extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'people_id';
    protected $table = 'people';

	
}
