<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_staff_group.
 *
 * @author  The scaffold-interface created at 2017-03-07 09:16:05am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Merchant_type_list extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'merchant_type_list_id';
    protected $table = 'merchant_type_list';
}
