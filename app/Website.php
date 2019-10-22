<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Website.
 *
 */
class Website extends Model
{
    protected $table = 'website';

    protected $primaryKey = 'social_id';

    public $timestamps = false;
}
