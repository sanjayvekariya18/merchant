<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Search_result_queue.
 *
 * @author  The scaffold-interface created at 2018-03-07 01:10:35pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Search_result_queue extends Model
{
	

    protected $table = 'search_result_queue';
    public $timestamps = false;
    protected $primaryKey = 'id';
	
}
