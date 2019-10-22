<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RegexMapTargetFields.
 *
 */
class RegexMapTargetFields extends Model
{
    protected $table = 'regex_map_target_fields';

    protected $primaryKey = 'map_id';

    public $timestamps = false;
}
