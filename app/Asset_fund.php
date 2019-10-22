<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Asset_fund.
 *
 */
class Asset_fund extends Model
{
    protected $table = 'asset_fund';

	protected $primaryKey = 'fund_id';

    public $timestamps = false;
}
