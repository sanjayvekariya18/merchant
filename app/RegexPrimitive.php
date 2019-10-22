<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RegexPrimitive.
 *
 */
class RegexPrimitive extends Model
{
    protected $table      = 'regex_primitive';
    public $timestamps    = false;
    protected $primaryKey = 'pattern_id';
}
