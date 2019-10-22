<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Website_category_list.
 *
 */
class Website_category_list extends Model
{
    protected $table = 'website_category_list';

    protected $primaryKey = 'list_id';

    public $timestamps = false;
}
