<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Regex_map_identity.
 *
 */
class Regex_map_identity extends Model
{
    protected $table = 'regex_map_identity';

	protected $primaryKey = 'regex_id';

    public $timestamps = false;
}
