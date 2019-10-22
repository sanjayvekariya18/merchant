<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductionJson extends Model
{
    protected $connection = 'mysqlDynamicConnector';

    protected $table = 'production_json';

    protected $primaryKey = 'production_id';

    public $timestamps = false;
}
