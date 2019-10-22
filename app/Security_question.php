<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_security_question.
 *
 * @author  The scaffold-interface created at 2017-03-01 12:27:50pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Security_question extends Model
{
	
	protected $table = 'security_questions';
    public $timestamps = false;
    protected $primaryKey = 'question_id';
	
}
