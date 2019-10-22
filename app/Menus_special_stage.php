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
class Menus_special_stage extends Model
{
	
	
    protected $table = 'product_specials_stage';

    protected $primaryKey = 'product_special_id';

    public $timestamps = false;
	
}
