<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Asset_flow.
 */
class Asset_flow extends Model
{
    protected $table = 'asset_flow';

	protected $primaryKey = 'flow_id';

    public $timestamps = false;
}
