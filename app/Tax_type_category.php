<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Tax_type_category.
 *
 */
class Tax_type_category extends Model
{
    protected $table = 'tax_type_category';

    protected $primaryKey = 'category_id';

    public $timestamps = false;
}
