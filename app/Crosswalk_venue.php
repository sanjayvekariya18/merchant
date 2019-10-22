<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Crosswalk_venue extends Model
{
    protected $connection = 'mysqlDynamicConnector';

    protected $table = 'crosswalk_venue';

    protected $primaryKey = 'crosswalk_id';

    public $timestamps = false;
}
