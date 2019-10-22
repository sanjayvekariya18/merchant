<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Website_domain.
 *
 * @author  The scaffold-interface created at 2018-03-05 02:19:27pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Website_domain extends Model
{
	
	
    protected $table = 'website_domain';
    public $timestamps = false;
    protected $primaryKey = 'website_domain_id';


	
}
