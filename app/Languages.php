<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Languages.
 */
class Languages extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'language_id';

    protected $table = 'languages';
}
