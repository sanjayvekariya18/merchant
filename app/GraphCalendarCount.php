<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Hase_staff_group.
 *
 * @author  The scaffold-interface created at 2017-03-07 09:16:05am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class GraphCalendarCount extends Model
{
    public $timestamps    = false;
    protected $primaryKey = 'calendar_count_id';
    protected $table      = 'graph_calendar_count';
}
