<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_promotion.
 *
 * @author  The scaffold-interface created at 2017-03-18 08:36:45am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Promotion extends Model
{
	
	
    protected $table = 'promotions';
    public $timestamps = false;
    protected $primaryKey = 'promotion_id';

	
}
