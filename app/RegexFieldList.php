<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RegexFieldList.
 */
class RegexFieldList extends Model
{

    protected $table      = 'regex_field_list';
    public $timestamps    = false;
    protected $primaryKey = 'regex_list_id';

}
