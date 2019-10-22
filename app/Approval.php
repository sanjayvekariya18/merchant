<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $table = 'approval';
    protected $primaryKey = 'approval_id';
    public $timestamps = false;
    public static function requestTimeDetails($merchantId)
    {
        $requestTimeDetails= Approval::where('request_merchant_id',"=",$merchantId)->first();
        if($requestTimeDetails != ''){
       		return $requestTimeDetails;
		}
		else {
			return "";
		}
	}
}
