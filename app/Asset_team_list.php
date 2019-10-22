<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Asset_team_list.
 *
 */
class Asset_team_list extends Model
{
    protected $table = 'asset_team_list';

	protected $primaryKey = 'list_id';

    public $timestamps = false;
}
