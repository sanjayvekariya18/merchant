<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class trade basket.
 *
 * @author  The scaffold-interface created at 2018-02-06 01:54:39pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Trade_basket extends Model
{
	
    public $timestamps = false;
    protected $primaryKey = 'basket_id';
    protected $table = 'trade_basket';

}
