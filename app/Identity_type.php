<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Identity_type.
 *
 * @author  The scaffold-interface created at 2018-02-18 02:18:07pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Identity_type extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'identity_type_id';
    protected $table = 'identity_type';

	
}
