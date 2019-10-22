<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_import.
 *
 * @author  The scaffold-interface created at 2017-03-18 03:34:36am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Imports extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'import_id';
    protected $table = 'imports';

}
