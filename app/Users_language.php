<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Users_language.
 *
 * @author  The scaffold-interface created at 2017-05-12 03:07:57pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Users_language extends Model
{
	
	
    protected $table = 'users_languages';

    protected $primaryKey = 'id';

    public $timestamps = false;

	
}
