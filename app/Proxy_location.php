<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Account.
 *
 * @author  The scaffold-interface created at 2018-02-06 01:54:39pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Proxy_location extends Model
{
    protected $connection = 'mysqlDynamicConnector';
    public $timestamps    = false;
    protected $primaryKey = 'proxy_id';
    protected $table      = 'proxy_location';

}
