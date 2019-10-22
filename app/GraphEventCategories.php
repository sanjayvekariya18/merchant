<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GraphEventCategories extends Model
{
	protected $table = 'graph_event_categories';
	
	protected $primaryKey = 'event_category_id';

	public $timestamps = false;
}
