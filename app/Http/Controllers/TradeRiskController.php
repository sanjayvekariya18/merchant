<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Trade_Risk;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;
use URL;
use DB;
/**
 * Class PositionController.
 *
 * @author  The scaffold-interface created at 2018-02-10 04:57:00pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class TradeRiskController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Trade_risk');

        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function TradeRiskIndex()
    {
        $title = 'Index - position';
        return view('trade_risk.tradeRiskIndex',compact('title'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    int $id
     * @return  \Illuminate\Http\Response
     */

    public function RetrieveTradeRiskList($filterValue) { 

        $where = array();
        $merchantTypeInfo = PermissionTrait::getMerchantType();
        $merchantType = $merchantTypeInfo->merchant_type_id;
        if($this->merchantId == 0){
                //$where['merchant_type.merchant_root_id'] = $merchantType;
        }else{
            if($this->roleId == 4){
                $where['trade_risk.merchant_id'] = $this->merchantId;
            }else{
                $where['trade_risk.merchant_id'] = $this->merchantId;
                $where['trade_risk.location_city_id'] = $this->locationId; 
            }
        }

        $tradeRiskListData = Trade_Risk::select(
                        'trade_risk.*',
                        'merchant_identity.identity_name as merchant_name',
                        'location_city.city_name as location_name',
                        
                        'staff_groups.staff_group_name',
                        'staffs.staff_name',
                        
                        'customer_identity.identity_name as customer_name',
                        'customer_identity.identity_code as customer_code',
                        'timezone.timezone_name',
                        'account.account_code_long',
                        'asset_identity.identity_name as asset_name',
                        'asset_identity.identity_code as asset_code'
                        )

                        ->leftjoin('merchant','merchant.merchant_id','trade_risk.merchant_id')
                        ->leftjoin('identity as merchant_identity','merchant_identity.identity_id','merchant.identity_id')

                        ->leftjoin('location_city','location_city.city_id','trade_risk.location_city_id')

                        ->leftjoin('staffs','staffs.staff_id','trade_risk.staff_id')

                        ->leftjoin('staff_groups','staff_groups.staff_group_id','trade_risk.group_id')

                        ->leftjoin('customers','customers.customer_id','trade_risk.customer_id')
                        ->leftjoin('identity as customer_identity','customer_identity.identity_id','customers.identity_id')

                        ->leftjoin('account','account.account_id','trade_risk.staff_account_id')

                        ->leftjoin('timezone','timezone.timezone_id','trade_risk.entry_timezone')

                        // ->leftjoin('identity as account_identity','account_identity.identity_id','account.identity_id')

                        ->leftjoin('asset as asset_into','asset_into.asset_id','trade_risk.asset_id')

                        ->leftjoin('identity_asset as asset_identity','asset_identity.identity_id','asset_into.identity_id')

                        ->leftjoin('merchant_type_list','merchant_type_list.merchant_id','merchant.merchant_id')
                        ->leftjoin('merchant_type','merchant_type.merchant_type_id','merchant_type_list.merchant_type_id')

                        ->where(function($q) use ($where){
                            foreach($where as $key => $value){
                                $q->where($key, '=', $value);
                            }
                        })->get();
        return $tradeRiskListData->toArray();
    }
}
