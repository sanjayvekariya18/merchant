<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Tax_type.
 *
 */
class Tax_type extends Model
{
    protected $table = 'tax_type';

    protected $primaryKey = 'type_id';

    public $timestamps = false;
}
