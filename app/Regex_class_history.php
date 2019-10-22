<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Regex_class_history.
 */
class Regex_class_history extends Model
{

    protected $table      = 'regex_class_history';
    public $timestamps    = false;
    protected $primaryKey = 'history_id';

}
