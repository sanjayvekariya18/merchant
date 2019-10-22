<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Regex_block_level.
 */
class Regex_block_level extends Model
{
    protected $table = 'regex_block_level';

	protected $primaryKey = 'id';

    public $timestamps = false;
}
