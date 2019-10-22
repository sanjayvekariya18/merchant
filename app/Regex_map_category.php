<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Regex_category_list.
 *
 */
class Regex_map_category extends Model
{
    protected $table = 'regex_map_category';

    protected $primaryKey = 'mapping_id';

    public $timestamps = false;
}
