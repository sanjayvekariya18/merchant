<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Keyword extends Model
{	
    public $timestamps = false;
    
    protected $table = 'keyword';
    protected $primaryKey = 'keyword_id';

	
}
