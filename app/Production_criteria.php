<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Production_criteria extends Model
{
	protected $connection = 'mysqlDynamicConnector';
	
	protected $table = 'production_criteria';
	
	protected $primaryKey = 'criteria_id';

	public $timestamps = false;
}
