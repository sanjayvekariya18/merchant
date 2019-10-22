<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Identity_website.
 *
 */
class Identity_website extends Model
{
    protected $table = 'identity_website';
    public $timestamps = false;
    protected $primaryKey = 'identity_id';
}
