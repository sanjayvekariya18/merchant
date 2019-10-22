<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RegexCategoryName.
 *
 * @author  The scaffold-interface created at 2018-02-27 02:24:29pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class RegexCategoryName extends Model
{

    protected $table      = 'regex_category_name';
    public $timestamps    = false;
    protected $primaryKey = 'name_id';

}
