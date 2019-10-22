<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Asset_fund.
 *
 */
class Opponent extends Model
{
    protected $connection = 'mysqlDynamicConnector';

    protected $table = 'opponent';

    protected $primaryKey = 'opponent_id';

    public $timestamps = false;
}
