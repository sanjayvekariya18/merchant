<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Regex_example.
 *
 * @author  The scaffold-interface created at 2018-02-27 02:24:29pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class RegexSplitPrimitive extends Model
{
	
	
    protected $table = 'regex_split_primitive';
    public $timestamps = false;
    protected $primaryKey = 'split_primitive_id';
	
}
