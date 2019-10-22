<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Proxy_type.
 */
class Premises_operation extends Model
{
   
    public $timestamps    = false;
    protected $primaryKey = 'operation_id';
    protected $table      = 'premises_operation';

}
