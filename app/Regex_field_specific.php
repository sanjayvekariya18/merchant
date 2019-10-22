<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Regex_field_specific.
 */
class Regex_field_specific extends Model
{
    protected $table      = 'regex_field_specific';
    public $timestamps    = false;
    protected $primaryKey = 'field_id';
}
