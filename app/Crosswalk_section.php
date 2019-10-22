<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Crosswalk_section extends Model
{
    protected $connection = 'mysqlDynamicConnector';

    protected $table = 'crosswalk_section';

    protected $primaryKey = 'Exchange_id';

    public $timestamps = false;
}
