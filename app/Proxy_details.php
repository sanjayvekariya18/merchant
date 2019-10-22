<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Proxy_details.
 */
class Proxy_details extends Model
{

    protected $connection = 'mysqlDynamicConnector';
    public $timestamps    = false;
    protected $primaryKey = 'source_id';
    protected $table      = 'proxy_details';

}
