<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Social.
 *
 * @author  The scaffold-interface created at 2018-02-14 08:06:23pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Social extends Model
{
	
    public $timestamps = false;
    protected $primaryKey = 'social_id';
    protected $table = 'Social';

	
}
