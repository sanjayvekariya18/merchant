<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Identity_payee.
 *
 */
class Identity_payee_list extends Model
{
    protected $table = 'identity_payee_list';

	protected $primaryKey = 'list_id';

    public $timestamps = false;
}
