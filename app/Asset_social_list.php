<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Asset_social_list.
 */
class Asset_social_list extends Model
{
    protected $table = 'asset_social_list';

	protected $primaryKey = 'list_id';

    public $timestamps = false;
}
