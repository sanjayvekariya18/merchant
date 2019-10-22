<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
	protected $connection = 'mysqlDynamicConnector';
	
	protected $table = 'sales';
	
	protected $primaryKey = 'invoice_id';

	public $timestamps = false;
}
