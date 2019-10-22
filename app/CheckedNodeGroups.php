<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CheckedNodeGroups.
 *
 */
class CheckedNodeGroups extends Model
{
    protected $table = 'checked_node_groups';

    protected $primaryKey = 'checked_id';

    public $timestamps = false;
}
