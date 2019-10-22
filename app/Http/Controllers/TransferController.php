<?php

namespace App\Http\Controllers;

use App\Helpers\ConnectionManager;
use App\Http\Traits\PermissionTrait;
use App\Module;
use App\ModuleFields;
use App\ModuleFieldTypes;
use App\Transfer;
use Illuminate\Http\Request;
use Validator;

class TransferController extends PermissionsController
{
    use PermissionTrait;
    const INIT_VALUE = 0;
    public $show_action  = true;
    public $view_col     = 'identity_merchant_group_id';
    public $listing_cols = ['transfer_id', 'identity_merchant_group_id', 'category_id', 'category_name', 'invoice_id', 'listing_id', 'sales_date', 'exchange_id', 'exchange_name', 'event_id', 'event_name', 'event_date', 'event_time', 'venue_id', 'venue_name', 'section', 'row', 'item_price', 'seat_quantity', 'seat_start', 'start_end', 'invoice_amount', 'invoice_commission', 'net_amount', 'cost', 'roi_dollar', 'roi_percent', 'payment_date', 'payment_type', 'status'];

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('ticket_event', 'mysqlDynamicConnector');
        if (strcmp($connectionStatus['type'], "error") == self::INIT_VALUE) {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
        }
    }

    /**
     * Display a listing of the Transfer.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $module = Module::get('Transfer');

        if (Module::hasAccess($module->id, "access")) {
            return View('transfer.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => $this->listing_cols,
                'module'       => $module,
            ]);
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getTransfer(Request $request)
    {
        $total_records = self::INIT_VALUE;

        $transferDetails = Transfer::
            select('transfer.*')->orderBy('transfer.sales_date', 'DESC');

        if (isset($request->filter)) {
            $searchFilter = $request->filter['filters'][self::INIT_VALUE]['value'];
            if ($searchFilter) {
                $transferDetails->where(function ($query) use ($searchFilter, $request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        if ($filterValue['field'] == 'sales_date') {
                            $searchFilter = str_replace(' ', '', $searchFilter);
                        }
                        $query->orWhere('transfer.' . $filterValue['field'] . '', 'LIKE', '%' . $searchFilter . '%');
                    }
                });
            }
        }
        if (isset($request->sort)) {
            $sortDirection = $request->sort[self::INIT_VALUE]['dir'];
            $sortFields    = $request->sort[self::INIT_VALUE]['field'];
            $transferDetails->orderBy($sortFields, $sortDirection);
        }
        if (isset($request->status)) {
            $transferDetails->where('transfer.status','=','Payment Pending');
        } else {
            $transferDetails->where('transfer.status','!=','Payment Pending');
        }
        $total_records = $total_records + $transferDetails->get()->count();
        if (isset($request->take)) {
            $transferDetails->offset($request->skip)->limit($request->take);
        }
        $transferValues      = $transferDetails->get();
        $templateDefineArray = array("Multiselect");
        $fields_popup        = ModuleFields::getModuleFields('Transfer');
        $module              = Module::where('name', 'Transfer')->orderBy('id', 'desc')->first();
        foreach ($transferValues as $key => $value) {
            for ($j = self::INIT_VALUE; $j < count($this->listing_cols); $j++) {
                $col = $this->listing_cols[$j];
                if (isset($value[$col]) && $fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $transferValues[$key][$fields_popup[$col]->popup_field_id] = ModuleFields::getKendoFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    } else {
                        $transferValues[$key][$fields_popup[$col]->popup_field_name] = ModuleFields::getFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    }

                } else if (isset($value[$col]) && !!$value[$col] && isset($fields_popup[$col]) && !!$fields_popup[$col] && starts_with($fields_popup[$col]->popup_vals, "[")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $TestingEventValues[$key][$fields_popup[$col]->colname] = json_decode($TestingEventValues[$key][$fields_popup[$col]->colname], true);
                    }

                }
            }
        }
        foreach ($transferValues as $key => $value) {                                    
            $transferValues[$key]->sales_date    = date('Ymd', strtotime($value->sales_date));
        }
        $Transferdata['transfer'] = $transferValues->toArray();
        $Transferdata['total']    = $total_records;
        return json_encode($Transferdata);
    }

    /**
     * Update the specified transfer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateTransfer(Request $request)
    {
        $callback = $request->callback;
        if (Module::hasAccess("Transfer", "manage")) {

            $rules = Module::validateRules("Transfer", $request, true);

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            Module::updateRow("Transfer", $request, $request->transfer_id);
            return $callback . "(" . json_encode($request) . ")";

        } else {
            return $callback . "(" . json_encode($request) . ")";
        }
    }

    /**
     * Remove the specified transfer from storage.
     *
     * @param  object  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteTransfer(Request $request)
    {
        $callback = $request->callback;
        if (Module::hasAccess("Transfer", "delete")) {
            Transfer::find($request->transfer_id)->delete();
            return $callback . "(" . json_encode($request) . ")";
        } else {
            return $callback . "(" . json_encode($request) . ")";
        }
    }
}
