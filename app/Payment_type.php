<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Payment_type.
 *
 */
class Payment_type extends Model
{
    protected $table = 'payment_type';

	protected $primaryKey = 'type_id';

    public $timestamps = false;
}
