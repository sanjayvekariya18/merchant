<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryHistory extends Model
{
    protected $connection = 'mysqlDynamicConnector';

    protected $table = 'inventory_history';

    protected $primaryKey = 'service_id';

    public $timestamps = false;
}
