<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_chatbot_communication.
 *
 * @author  The scaffold-interface created at 2017-04-08 01:17:22pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Chatbot_communication extends Model
{
	
	
    protected $table = 'communication_stage';

        protected $primaryKey = 'communication_id';

    public $timestamps = false;

	
}
