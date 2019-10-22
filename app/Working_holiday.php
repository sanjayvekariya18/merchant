<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_working_holiday.
 */
class Working_holiday extends Model
{
    protected $table = 'working_holidays';

	protected $primaryKey = 'holiday_id';

    public $timestamps = false;
}
