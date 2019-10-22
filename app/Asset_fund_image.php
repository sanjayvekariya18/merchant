<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Asset_fund.
 *
 */
class Asset_fund_image extends Model
{
    protected $table = 'asset_fund_image';

	protected $primaryKey = 'image_id';

    public $timestamps = false;
}
