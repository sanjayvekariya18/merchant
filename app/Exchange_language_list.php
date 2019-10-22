<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Exchange_language_list.
 *
 */
class Exchange_language_list extends Model
{
    protected $table = 'exchange_language_list';

	protected $primaryKey = 'list_id';

    public $timestamps = false;
}
