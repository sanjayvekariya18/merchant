<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Fee_type.
 *
 */
class Fee_type extends Model
{
    protected $table = 'fee_type';

	protected $primaryKey = 'type_id';

    public $timestamps = false;
}
