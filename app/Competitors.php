<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Competitors extends Model
{
    protected $connection = 'mysqlDynamicConnector';

    protected $table = 'competitors';

    protected $primaryKey = 'service_id';

    public $timestamps = false;
}
