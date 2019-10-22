<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Exchange_asset_pair.
 *
 */
class Exchange_asset_pair extends Model
{
    protected $table = 'exchange_asset_pairs';

    protected $primaryKey = 'pairs_id';

    public $timestamps = false;
}
