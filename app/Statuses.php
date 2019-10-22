<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_statuses.
 *
 */
class Statuses extends Model
{
    protected $table = 'statuses';

	protected $primaryKey = 'status_id';

    public $timestamps = false;
}
