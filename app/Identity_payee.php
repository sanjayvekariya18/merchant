<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Identity_payee.
 *
 */
class Identity_payee extends Model
{
    protected $table = 'identity_payee';

	protected $primaryKey = 'identity_id';

    public $timestamps = false;
}
