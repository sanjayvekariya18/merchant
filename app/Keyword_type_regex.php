<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Keyword_type_regex.
 *
 * @author  The scaffold-interface created at 2018-02-26 06:31:34pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Keyword_type_regex extends Model
{
	
	
    public $timestamps = false;
    
    protected $table = 'regex_production';
     protected $primaryKey = 'keyword_id';

	
}
