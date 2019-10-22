<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompetitorsHistory extends Model
{
    protected $connection = 'mysqlDynamicConnector';

    protected $table = 'competitors_history';

    protected $primaryKey = 'service_id';

    public $timestamps = false;
}
