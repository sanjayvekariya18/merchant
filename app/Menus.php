<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*use App\Helpers\LAHelper;*/
/**
 * Class Hase_Menus.
 *
 * @author  The scaffold-interface created at 2017-03-19 06:03:27am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Menus extends Model
{

    public $timestamps    = false;
    protected $primaryKey = 'id';
    protected $table      = 'menus';

    protected $guarded = [

    ];

}
