<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticker extends Model
{
    protected $connection = 'mysqlDynamicConnector';

    protected $table = 'ticker';

    protected $primaryKey = 'service_id';

    public $timestamps = false;
}
