<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Fund_type.
 *
 */
class Fund_type extends Model
{
    protected $table = 'fund_type';

	protected $primaryKey = 'type_id';

    public $timestamps = false;
}
