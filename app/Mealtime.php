<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_Menucategories.
 *
 * @author  The scaffold-interface created at 2017-03-03 09:44:43am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Mealtime extends Model
{
	
	
    protected $table = 'hase_mealtimes';

    protected $primaryKey = 'mealtime_id';

    public $timestamps = false;
}
