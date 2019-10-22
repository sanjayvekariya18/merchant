<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Proxy_type.
 */
class Proxy_type extends Model
{

    protected $connection = 'mysqlDynamicConnector';
    public $timestamps    = false;
    protected $primaryKey = 'type_id';
    protected $table      = 'proxy_type';

}
