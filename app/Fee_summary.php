<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Fee_summary.
 *
 */
class Fee_summary extends Model
{
    protected $table = 'fee_summary';

	protected $primaryKey = 'summary_id';

    public $timestamps = false;
}
