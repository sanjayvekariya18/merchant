<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Crosswalk_production extends Model
{
    protected $connection = 'mysqlDynamicConnector';

    protected $table = 'crosswalk_production';

    protected $primaryKey = 'crosswalk_id';

    public $timestamps = false;
}
