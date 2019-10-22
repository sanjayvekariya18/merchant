<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Payee.
 *
 */
class Payee extends Model
{
    protected $table = 'payee';

	protected $primaryKey = 'payee_id';

    public $timestamps = false;
}
