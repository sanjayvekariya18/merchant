<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_approval_group_list.
 *
 * @author  The scaffold-interface created at 2017-04-05 04:16:05pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Approval_group_list extends Model
{
	
	
    public $timestamps = false;
    
    protected $table = 'approval_group_list';
    protected $primaryKey = 'staff_group_list_id';

	
}
