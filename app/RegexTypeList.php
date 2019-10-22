<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegexTypeList extends Model
{

    protected $table      = 'regex_type_list';
    public $timestamps    = false;
    protected $primaryKey = 'regex_type_id';

}
