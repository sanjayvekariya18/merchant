<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Purchase.
 *
 */
class Purchase extends Model
{
    protected $connection = 'mysqlDynamicConnector';

    protected $table = 'purchase';

    protected $primaryKey = 'production_id';

    public $timestamps = false;
}
