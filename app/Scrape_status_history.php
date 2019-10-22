<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Scrape_status_history.
 *
 */
class Scrape_status_history extends Model
{
    protected $table = 'scrape_status_history';

    protected $primaryKey = 'history_id';

    public $timestamps = false;
}
