<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Asset_move.
 *
 */
class Asset_move extends Model
{
    protected $table = 'asset_move';

	protected $primaryKey = 'move_id';

    public $timestamps = false;
}
