<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Exchange_asset_list.
 */
class Exchange_asset_list extends Model
{
    protected $table = 'exchange_asset_list';

	protected $primaryKey = 'list_id';

    public $timestamps = false;
}
