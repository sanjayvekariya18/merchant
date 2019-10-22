<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Block_delimiter.
 *
 */
class Block_delimiter extends Model
{
    protected $table = 'regex_block_delimiter';
    public $timestamps = false;
    protected $primaryKey = 'id';	
}
