<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Social.
 *
 * @author  The scaffold-interface created at 2018-02-14 08:06:23pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Database_manager extends Model
{
	
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'database_manager';
	
	public static function getDBProvider() {
        $databaseManagerValues = Database_manager::all();
        $resultValues = array();
        foreach ($databaseManagerValues as $databaseManager) {
            $resultValues[$databaseManager['id']] = $databaseManager['provider_name'];
        }
        return $resultValues;
    }
}
