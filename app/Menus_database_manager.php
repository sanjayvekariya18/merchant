<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class menus_database_manager.
 *
 * @author  The scaffold-interface created at 2018-02-14 08:06:23pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Menus_database_manager extends Model
{
	
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'menus_database_manager';
	
}
