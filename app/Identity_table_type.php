<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Identity_type.
 *
 */
class Identity_table_type extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'type_id';
    protected $table = 'identity_table_type';
}
