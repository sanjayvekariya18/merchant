<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Regex_pagination.
 */
class Regex_pagination extends Model
{
    protected $table = 'regex_pagination';

	protected $primaryKey = 'pagination_id';

    public $timestamps = false;
}
