<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_menu.
 *
 * @author  The scaffold-interface created at 2017-03-03 09:44:43am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Menu extends Model
{
    
    
    protected $table = 'product';

    protected $primaryKey = 'product_id';

    public $timestamps = false;
    public function categoryName()
    {
        return $this->hasOne('App\Merchant_retail_category_type','category_type_id','category_type_id');
    }

    public function menusSpecials($menu_id)
    {
        return DB::table('product_specials')->where('product_id', '=', $menu_id)->get();
    }
}
