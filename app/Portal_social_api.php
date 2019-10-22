<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Account_type.
 *
 * @author  The scaffold-interface created at 2018-02-18 01:01:04pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Portal_social_api extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'connectorid';
    protected $table = 'portal_social_api';

	
}
