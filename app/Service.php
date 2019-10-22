<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Asset_fund.
 *
 */
class Service extends Model
{
    protected $connection = 'mysqlDynamicConnector';

    protected $table = 'service';

    protected $primaryKey = 'service_id';

    public $timestamps = false;
}
