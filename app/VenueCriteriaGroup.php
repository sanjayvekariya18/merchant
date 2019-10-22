<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VenueCriteriaGroup extends Model
{
    protected $connection = 'mysqlDynamicConnector';

    protected $table = 'venue_criteria_group';

    protected $primaryKey = 'group_id';

    public $timestamps = false;
}
