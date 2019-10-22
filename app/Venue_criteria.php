<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venue_criteria extends Model
{
    protected $connection = 'mysqlDynamicConnector';

    protected $table = 'venue_criteria';

    protected $primaryKey = 'criteria_id';

    public $timestamps = false;
}
