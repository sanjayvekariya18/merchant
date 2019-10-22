<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Portal_exception extends Model
{
	protected $table = 'portal_exception';
	
	protected $primaryKey = 'id';

	public $timestamps = false;
}
