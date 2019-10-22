<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Purchasing.
 *
 */
class Purchasing extends Model
{
    protected $connection = 'mysqlDynamicConnector';

    protected $table = 'purchasing';

    protected $primaryKey = 'listing_id';

    public $timestamps = false;
}
