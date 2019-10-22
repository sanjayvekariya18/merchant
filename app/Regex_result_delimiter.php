<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Regex_result_delimiter.
 *
 */
class Regex_result_delimiter extends Model
{
    protected $table = 'regex_result_delimiter';

    protected $primaryKey = 'result_id';

    public $timestamps = false;
}
