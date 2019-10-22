<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $connection = 'mysqlDynamicConnector';

    protected $table = 'transfer';

    protected $primaryKey = 'transfer_id';

    public $timestamps = false;
}
