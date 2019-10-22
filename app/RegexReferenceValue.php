<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RegexReferenceValue.
 *
 */
class RegexReferenceValue extends Model
{
    protected $table = 'regex_reference_value';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
