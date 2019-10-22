<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RegexType.
 *
 */
class RegexType extends Model
{
    protected $table      = 'regex_type';
    public $timestamps    = false;
    protected $primaryKey = 'type_id';
}
