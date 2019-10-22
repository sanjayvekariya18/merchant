<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_menu.
 *
 * @author  The scaffold-interface created at 2017-03-03 09:44:43am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Identity_bank extends Model
{
	
	
    protected $table = 'identity_bank';

    protected $primaryKey = 'identity_id';

    public $timestamps = false;
}
