<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_merchant.
 *
 */
class Identity_menu extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'identity_id';
    protected $table = 'identity_product';
}

