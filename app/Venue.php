<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_exhibition.
 *
 * @author  The scaffold-interface created at 2017-05-24 04:20:11pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Venue extends Model
{
	
	
    protected $table = 'venue';
    public $timestamps = false;
    protected $primaryKey = 'venue_id';
	
}
