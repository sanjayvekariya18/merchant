<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Regex_result_pattern.
 *
 */
class Regex_result_pattern extends Model
{
    protected $table = 'regex_result_pattern';

    protected $primaryKey = 'result_id';

    public $timestamps = false;
}
