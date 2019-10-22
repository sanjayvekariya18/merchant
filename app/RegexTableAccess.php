<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RegexTableAccess.
 */
class RegexTableAccess extends Model
{

    protected $table      = 'regex_table_access';
    public $timestamps    = false;
    protected $primaryKey = 'access_id';

}
