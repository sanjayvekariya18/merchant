<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TickerHistory extends Model
{
    protected $connection = 'mysqlDynamicConnector';

    protected $table = 'ticker_history';

    protected $primaryKey = 'service_id';

    public $timestamps = false;
}
