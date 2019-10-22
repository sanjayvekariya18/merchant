<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Search_result_scrape.
 *
 * @author  The scaffold-interface created at 2018-03-09 06:25:29pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Search_result_scrape extends Model
{
	
	
    protected $table = 'search_result_scrape';
    public $timestamps = false;
    protected $primaryKey = 'id';

	
}
