<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instances_events extends Model
{
	protected $connection = 'mysqlDynamicConnector';
	
	protected $table = 'instances_events';
	
	protected $primaryKey = 'id';

	public $timestamps = false;
}
