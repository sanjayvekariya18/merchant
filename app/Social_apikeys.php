<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Social_apikeys.
 *
 * @author  The scaffold-interface created at 2018-02-14 08:06:23pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Social_apikeys extends Model
{
	
    public $timestamps = false;
    protected $primaryKey = 'keys_id';
    protected $table = 'social_apikeys';

	
}
