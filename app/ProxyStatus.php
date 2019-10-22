<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProxyStatus.
 */
class ProxyStatus extends Model
{

    protected $connection = 'mysqlDynamicConnector';
    public $timestamps    = false;
    protected $primaryKey = 'status_id';
    protected $table      = 'proxy_status';

}
