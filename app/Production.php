<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Asset_fund.
 *
 */
class Production extends Model
{
    protected $connection = 'mysqlDynamicConnector';

    protected $table = 'production';

    protected $primaryKey = 'production_id';

    public $timestamps = false;
}
