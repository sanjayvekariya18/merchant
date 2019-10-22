<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Regex_category_list.
 *
 */
class Regex_category_list extends Model
{
    protected $table = 'regex_category_list';

    protected $primaryKey = 'category_list_id';

    public $timestamps = false;
}
