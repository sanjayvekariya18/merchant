<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Approval_category.
 *
 * @author  The scaffold-interface created at 2017-03-06 08:51:43am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Approval_category extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'category_id';
    protected $table = 'approval_category';

	
}
