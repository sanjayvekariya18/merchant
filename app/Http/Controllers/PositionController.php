<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Position;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;
use URL;
use Session;
use Redirect;
use DB;

/**
 * Class PositionController.
 *
 */
class PositionController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Position_view');

        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
    }
    
    public function customerIndex()
    {
        $title = 'Index - position';
        return view('position.customerIndex',compact('title'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    int $id
     * @return  \Illuminate\Http\Response
     */

    public function RetrieveCustomerPositionList($filterValue) { 
        $where = array();
        // $merchantTypeInfo = PermissionTrait::getMerchantType();
        // $merchantType = $merchantTypeInfo->merchant_type_id;
        if($this->merchantId == 0){
                //$where['merchant_type.merchant_root_id'] = $merchantType;
        }else{
            if($this->roleId == 4){
                $where['trade_positions.merchant_id'] = $this->merchantId;
            }else{
                $where['trade_positions.merchant_id'] = $this->merchantId;
                $where['trade_positions.location_id'] = $this->locationId; 
            }
        }
        $positionListData = Position::select(
            'trade_positions.*',
            DB::raw('(trade_positions.asset_price * trade_positions.asset_quantity) as monthly_pnl'),
            DB::raw('(trade_positions.asset_price * trade_positions.asset_quantity) as daily_pnl'),
            'identity_merchant.identity_name as merchant_name',
            'location_city.city_name as location_name',
            'group_permissions.group_name',
            'identity_staff.identity_name as staff_name',
            'identity_customer.identity_name as customer_name',
            'identity_customer.identity_code as customer_code',
            'timezone.timezone_name',
            'account.account_code_long',
            'trade_order_side_type.side_type_name',
            'asset_into_identity.identity_name as asset_into_name',
            'asset_into_identity.identity_code as asset_into_code',
            'fee_asset_identity.identity_name as fee_asset_name',
            'fee_asset_identity.identity_code as fee_asset_code'
            )
            ->leftjoin('merchant','merchant.merchant_id','trade_positions.merchant_id')
            ->leftjoin('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
            ->leftjoin('location_list','location_list.list_id','trade_positions.location_id')
            ->leftjoin('location_city','location_city.city_id','location_list.location_city_id')

            ->leftjoin('staff','staff.staff_id','trade_positions.staff_id')
            ->leftjoin('identity_staff','identity_staff.identity_id','staff.identity_id')
            ->leftjoin('group_permissions','group_permissions.group_id','trade_positions.group_id')
            ->leftjoin('customers','customers.customer_id','trade_positions.customer_id')
            ->leftjoin('identity_customer','identity_customer.identity_id','customers.identity_id')
            ->leftjoin('account','account.account_id','trade_positions.staff_account_id')
            ->leftjoin('exchange','exchange.exchange_id','trade_positions.exchange_id')
            ->leftjoin('identity_exchange','exchange.identity_id','identity_exchange.identity_id')
            ->leftjoin('timezone','timezone.timezone_id','trade_positions.entry_timezone')
            ->leftjoin('trade_order_side_type','trade_order_side_type.side_type_id','trade_positions.side_type_id')
            ->leftjoin('asset as asset_into','asset_into.asset_id','trade_positions.asset_id')
            ->leftjoin('identity_asset as asset_into_identity','asset_into_identity.identity_id','asset_into.identity_id')
            ->leftjoin('asset as fee_asset','fee_asset.asset_id','trade_positions.fee_asset')
            ->leftjoin('identity_asset as fee_asset_identity','fee_asset_identity.identity_id','fee_asset.identity_id')
            ->leftjoin('merchant_type_list','merchant_type_list.merchant_id','merchant.merchant_id')
            ->leftjoin('merchant_type','merchant_type.merchant_type_id','merchant_type_list.merchant_type_id')
            ->where(function($q) use ($where) {
                foreach($where as $key => $value){
                    $q->where($key, '=', $value);
                }
        })->get();
        return $positionListData->toArray();
    }
}
