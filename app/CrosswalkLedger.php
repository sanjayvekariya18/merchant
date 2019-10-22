<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrosswalkLedger extends Model
{
    protected $connection = 'mysqlDynamicConnector';

    protected $table = 'crosswalk_ledger';

    protected $primaryKey = 'ledger_id';

    public $timestamps = false;
}
