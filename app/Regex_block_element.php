<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Regex_block_element.
 */
class Regex_block_element extends Model
{
    protected $table = 'regex_block_element';

	protected $primaryKey = 'element_id';

    public $timestamps = false;
}
