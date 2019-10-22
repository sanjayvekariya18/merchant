<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Keyword_list extends Model
{
    public $timestamps = false;
    
    protected $table = 'keyword_list';
     protected $primaryKey = 'keyword_list_id';

	
}
