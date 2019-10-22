<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Regex_result.
 *
 */
class Regex_result extends Model
{
    protected $table = 'regex_result';

	protected $primaryKey = 'result_id';

    public $timestamps = false;
}

