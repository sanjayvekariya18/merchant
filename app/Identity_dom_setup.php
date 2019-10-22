<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Identity_dom_setup extends Model
{
	protected $connection = 'mysqlDynamicConnector';
	
	protected $table = 'identity_dom_setup';
	
	protected $primaryKey = 'type_id';

	public $timestamps = false;
}
