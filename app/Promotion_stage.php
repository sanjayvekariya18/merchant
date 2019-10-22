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
class Promotion_stage extends Model
{
	
	
    protected $table = 'promotions_stage';
    public $timestamps = false;
    protected $primaryKey = 'promotion_id';

	
}
