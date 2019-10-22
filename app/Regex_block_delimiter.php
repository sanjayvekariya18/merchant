<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Regex_block_delimiter.
 *
 */
class Regex_block_delimiter extends Model
{
    protected $table = 'regex_block_delimiter';

	protected $primaryKey = 'delimiter_id';

    public $timestamps = false;
}

