<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Asset_fund.
 *
 */
class Ticket_venue extends Model
{
    protected $connection = 'mysqlDynamicConnector';

    protected $table = 'venue';

    protected $primaryKey = 'venue_id';

    public $timestamps = false;
}
