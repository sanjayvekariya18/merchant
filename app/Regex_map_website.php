<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Regex_map_website.
 *
 */
class Regex_map_website extends Model
{
    protected $table = 'regex_map_website';

	protected $primaryKey = 'regex_id';

    public $timestamps = false;
}
