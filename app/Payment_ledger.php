<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Payment_ledger.
 *
 */
class Payment_ledger extends Model
{
    protected $table = 'payment_ledger';

	protected $primaryKey = 'ledger_id';

    public $timestamps = false;
}
