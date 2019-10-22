<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $connection = 'mysqlDynamicConnector';

    protected $table = 'inventory';

    protected $primaryKey = 'service_id';

    public $timestamps = false;
}
