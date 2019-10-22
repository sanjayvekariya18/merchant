<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class People_social_list.
 */
class People_social_list extends Model
{
    protected $table = 'people_social_list';

	protected $primaryKey = 'list_id';

    public $timestamps = false;
}
