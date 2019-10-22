<?php

namespace App\Http\Controllers;

use App\Crosswalk_section;
use App\CrosswalkLedger;
use App\Helpers\ConnectionManager;
use App\Http\Traits\PermissionTrait;
use App\Module;
use App\ModuleFields;
use App\ModuleFieldTypes;
use DB;
use Illuminate\Http\Request;
use Validator;

class Crosswalk_exchangeController extends PermissionsController
{
    use PermissionTrait;
    const INIT_VALUE = 0;
    public $show_action  = true;
    public $view_col     = 'venue_id';
    public $listing_cols = ['Exchange_id', 'venue_id', 'data_sh', 'data_td'];

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
     * Display a listing of the Crosswalk_exchange.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $module = Module::get('Crosswalk_section');

        if (Module::hasAccess($module->id, "access")) {
            return View('crosswalk_exchange.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => $this->listing_cols,
                'module'       => $module,
            ]);
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getCrosswalkExchange(Request $request)
    {
        $total_records = self::INIT_VALUE;

        $crosswalkExchangeDetails = Crosswalk_section::
            select('crosswalk_section.*');

        if ($request->data_td == 'hideTDblank') {
            $crosswalkExchangeDetails->where(function ($query)  {
                 $query->where("crosswalk_section.data_td",'!=',null);
                 $query->Where("crosswalk_section.data_td",'!=','');
            });
        }
        if ($request->data_td == 'hideSHblank') {
            $crosswalkExchangeDetails->where(function ($query)  {
                $query->Where("crosswalk_section.data_sh",'!=',null);
                $query->Where("crosswalk_section.data_sh",'!=','');
            });
        }
        if ($request->data_td == 'showSHblank') {
            $crosswalkExchangeDetails->where(function ($query)  {
                $query->Where("crosswalk_section.data_sh",'=',null);
                $query->Where("crosswalk_section.data_td",'!=','');
                $query->Where("crosswalk_section.data_td",'!=',null);
            });
        }
        if ($request->data_td == 'showTDblank') {
            $crosswalkExchangeDetails->where(function ($query)  {
                $query->Where("crosswalk_section.data_td",'=',null);
                $query->Where("crosswalk_section.data_sh",'!=','');
                $query->Where("crosswalk_section.data_sh",'!=',null);
            });
        }
        if (isset($request->filter['filters'])) {
            $filterField = $request->filter['filters'][self::INIT_VALUE]['field'];
            $searchFilter = $request->filter['filters'][self::INIT_VALUE]['value'];
            if ($searchFilter) {
                $crosswalkExchangeDetails->where(function ($query) use ($searchFilter, $request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        if ($request->filter['filters'][self::INIT_VALUE]['operator'] == 'eq') {
                            $query->orWhere('crosswalk_section.' . $filterValue['field'], '=', $searchFilter);
                        } elseif ($request->filter['filters'][self::INIT_VALUE]['operator'] == 'neq') {
                            $query->orWhere('crosswalk_section.' . $filterValue['field'], '!=', $searchFilter);
                        } else {
                            $query->orWhere('crosswalk_section.' . $filterValue['field'], 'LIKE', '%' . $searchFilter . '%');
                        }
                    }
                });
            }
        }

        $total_records = $total_records + $crosswalkExchangeDetails->get()->count();
        if (isset($request->take)) {
            $crosswalkExchangeDetails->offset($request->skip)->limit($request->take);
        }
        $crosswalkExchangeValues = $crosswalkExchangeDetails->get();
        $templateDefineArray     = array("Multiselect");
        $fields_popup            = ModuleFields::getModuleFields('Crosswalk_section');
        $module                  = Module::where('name', 'Crosswalk_section')->orderBy('id', 'desc')->first();
        foreach ($crosswalkExchangeValues as $key => $value) {
            for ($j = self::INIT_VALUE; $j < count($this->listing_cols); $j++) {
                $col = $this->listing_cols[$j];
                if (isset($value[$col]) && $fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $crosswalkExchangeValues[$key][$fields_popup[$col]->popup_field_id] = ModuleFields::getKendoFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    } else {
                        $crosswalkExchangeValues[$key][$fields_popup[$col]->popup_field_name] = ModuleFields::getFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    }

                } else if (isset($value[$col]) && !!$value[$col] && isset($fields_popup[$col]) && !!$fields_popup[$col] && starts_with($fields_popup[$col]->popup_vals, "[")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $TestingEventValues[$key][$fields_popup[$col]->colname] = json_decode($TestingEventValues[$key][$fields_popup[$col]->colname], true);
                    }

                }
            }
        }
        foreach ($crosswalkExchangeValues as $key => $value) {
            $crosswalkExchangeValues[$key]->data_sh_td = $value->data_sh . " " . $value->data_td;
        }
        $CrosswalkExchangedata['crosswalkExchange'] = $crosswalkExchangeValues->toArray();
        $CrosswalkExchangedata['total']             = $total_records;
        return json_encode($CrosswalkExchangedata);
    }

    public function getVenueIdList(Request $request)
    {
        $venue_idDetails = DB::connection("mysqlDynamicConnector")->table("venue")->select("venue_id", "venue_name")->get()->toArray();
        return json_encode($venue_idDetails);
    }
    public function getDataTDList(Request $request)
    {
        $data_tdDetails = DB::connection("mysqlDynamicConnector")->table("crosswalk_section")->select("data_td","Exchange_id")->where("data_td", '!=', null)->where("data_td", '!=', '')->groupBy('data_td');
        if (isset($request->take)) {
            $data_tdDetails->offset($request->skip)->limit($request->take);
        }
        if (isset($request->filter['filters'])) {
            $searchFilter = $request->filter['filters'][self::INIT_VALUE]['value'];
            if ($searchFilter) {
                $data_tdDetails->where(function ($query) use ($searchFilter, $request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        $query->orWhere('crosswalk_section.' . $filterValue['field'] . '', 'LIKE', '%' . $searchFilter . '%');
                    }
                });
            }
        }
        $data_tdDetailsValue = $data_tdDetails->get();
        $data_tdDetails      = $data_tdDetailsValue->toArray();
        return json_encode($data_tdDetails);
    }
    public function getDataSHList(Request $request)
    {
        $data_shDetails = DB::connection("mysqlDynamicConnector")->table("crosswalk_section")->select("data_sh")->where("data_sh", '!=', null)->where("data_sh", '!=', '')->groupBy('data_sh');
        if (isset($request->take)) {
            $data_shDetails->offset($request->skip)->limit($request->take);
        }
        if (isset($request->filter['filters'])) {
            $searchFilter = $request->filter['filters'][self::INIT_VALUE]['value'];
            if ($searchFilter) {
                $data_shDetails->where(function ($query) use ($searchFilter, $request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        $query->orWhere('crosswalk_section.' . $filterValue['field'] . '', 'LIKE', '%' . $searchFilter . '%');
                    }
                });
            }
        }
        $data_shDetailsValue = $data_shDetails->get();
        $data_shDetails      = $data_shDetailsValue->toArray();
        return json_encode($data_shDetails);
    }

    /**
     * Update the specified crosswalk_exchange in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCrosswalkExchange(Request $request)
    {
        $callback = $request->callback;
        if (Module::hasAccess("Crosswalk_section", "manage")) {

            $rules = Module::validateRules("Crosswalk_exchange", $request, true);

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            if (is_array($request->data_td)) {
                foreach ($request->data_td as $key => $value) {
                    $request->data_td = $value;
                }
            } else {
                $request->data_td = $request->data_td;
            }
            if (is_array($request->data_sh)) {
                foreach ($request->data_sh as $key => $value) {
                    $request->data_sh = $value;
                }
            } else {
                $request->data_sh = $request->data_sh;
            }
            $oldCrosswalkDetails   = Crosswalk_section::where('Exchange_id', '=', $request->Exchange_id)->first();
            $crosswalkTypeDetail   = DB::connection("mysqlDynamicConnector")->table('crosswalk_type')->where('type_name', '=', 'section')->first();
                $oldCrosswalkData                         = $oldCrosswalkDetails->data_td;
                $crosswalk_exchange_ledger                = new CrosswalkLedger();
                $crosswalk_exchange_ledger->crosswalk_id  = $request->Exchange_id;
                $crosswalk_exchange_ledger->crosswalk_type= $crosswalkTypeDetail->type_id;
                $crosswalk_exchange_ledger->submit_date   = date("Ymd");
                $crosswalk_exchange_ledger->submit_time   = time();
                $crosswalk_exchange_ledger->identity_user = $this->userId;
                $crosswalk_exchange_ledger->data_old      = $oldCrosswalkData;
                $crosswalk_exchange_ledger->data_new      = $request->data_td;
                $crosswalk_exchange_ledger->save();
            $data_td_count=Crosswalk_section::where('data_td', '=', $request->data_td)->count();
            if($data_td_count != self::INIT_VALUE){
                $exchange_id = Module::updateRow("Crosswalk_section", $request, $request->Exchange_id);
                if (isset($exchange_id)) {
                    Crosswalk_section::where('data_sh', '=', null)->orwhere('data_sh', '=', '')->where('data_td', '=', $request->data_td)->delete();
                }
                $updateMessage = array("type" => "update","action" => "success", "message" => 'Trade Desk Updated');
            } else {
                $updateMessage = array("type" => "update" ,"action" => "error", "message" => 'Trade Desk Not Updated');
            }
            return $callback . "(" . json_encode($updateMessage) . ")";

        } else {
            return $callback . "(" . json_encode($request) . ")";
        }
    }

    /**
     * Remove the specified crosswalk_exchange from storage.
     *
     * @param  object  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteCrosswalkExchange(Request $request)
    {
        $callback = $request->callback;
        if (Module::hasAccess("Crosswalk_section", "delete")) {
            Crosswalk_section::find($request->Exchange_id)->delete();
            return $callback . "(" . json_encode($request) . ")";
        } else {
            return $callback . "(" . json_encode($request) . ")";
        }
    }
}
