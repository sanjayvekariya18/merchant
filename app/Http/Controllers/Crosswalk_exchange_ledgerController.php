<?php

namespace App\Http\Controllers;

use App\CrosswalkLedger;
use App\Portal_password;
use App\Helpers\ConnectionManager;
use App\Http\Traits\PermissionTrait;
use App\Module;
use App\ModuleFields;
use App\ModuleFieldTypes;
use Illuminate\Http\Request;
use Validator;

class Crosswalk_exchange_ledgerController extends PermissionsController
{
    use PermissionTrait;
    const INIT_VALUE = 0;
    public $show_action  = true;
    public $view_col     = 'crosswalk_id';
    public $listing_cols = ['ledger_id', 'crosswalk_id', 'submit_date', 'submit_time', 'identity_user', 'data_old', 'data_new'];

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
     * Display a listing of the Crosswalk_exchange_ledger.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $module = Module::get('Crosswalk_exchange_ledger');

        if (Module::hasAccess($module->id, "access")) {
            return View('crosswalk_exchange_ledger.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => $this->listing_cols,
                'module'       => $module,
            ]);
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getCrosswalkExchangeLedger(Request $request)
    {
        $total_records = self::INIT_VALUE;

        $crosswalkExchangeLedgerDetails = CrosswalkLedger::
            select('crosswalk_ledger.*','crosswalk_type.type_name')
            ->leftjoin('crosswalk_type', 'crosswalk_type.type_id', 'crosswalk_ledger.crosswalk_type');;

        if (isset($request->filter)) {
            $searchFilter = $request->filter['filters'][self::INIT_VALUE]['value'];
            if ($searchFilter) {
                $crosswalkExchangeLedgerDetails->where(function ($query) use ($searchFilter, $request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        $query->orWhere('crosswalk_ledger.' . $filterValue['field'] . '', 'LIKE', '%' . $searchFilter . '%');
                    }
                });
            }
        }

        $total_records = $total_records + $crosswalkExchangeLedgerDetails->get()->count();
        if (isset($request->take)) {
            $crosswalkExchangeLedgerDetails->offset($request->skip)->limit($request->take);
        }
        $crosswalkExchangeLedgerValues = $crosswalkExchangeLedgerDetails->get();
        $templateDefineArray           = array("Multiselect");
        $fields_popup                  = ModuleFields::getModuleFields('Crosswalk_exchange_ledger');
        $module                        = Module::where('name', 'Crosswalk_exchange_ledger')->orderBy('id', 'desc')->first();
        foreach ($crosswalkExchangeLedgerValues as $key => $value) {
            for ($j = self::INIT_VALUE; $j < count($this->listing_cols); $j++) {
                $col = $this->listing_cols[$j];
                if (isset($value[$col]) && $fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $crosswalkExchangeLedgerValues[$key][$fields_popup[$col]->popup_field_id] = ModuleFields::getKendoFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    } else {
                        $crosswalkExchangeLedgerValues[$key][$fields_popup[$col]->popup_field_name] = ModuleFields::getFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    }

                } else if (isset($value[$col]) && !!$value[$col] && isset($fields_popup[$col]) && !!$fields_popup[$col] && starts_with($fields_popup[$col]->popup_vals, "[")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $TestingEventValues[$key][$fields_popup[$col]->colname] = json_decode($TestingEventValues[$key][$fields_popup[$col]->colname], true);
                    }

                }
            }
        }
        foreach ($crosswalkExchangeLedgerValues as $key => $value) {
            $connectionStatus                                   = ConnectionManager::setDbConfig('Hase_staff');
            $usersDetails                                       = Portal_password::where("user_id", $value->identity_user)->first();
            $crosswalkExchangeLedgerValues[$key]->identity_user = $usersDetails->username;
            $datetime                                         = json_decode(PermissionTrait::covertToLocalTz($value->submit_time));
            $crosswalkExchangeLedgerValues[$key]->submit_date = $datetime->date;
            $crosswalkExchangeLedgerValues[$key]->submit_time = $datetime->time;
        }
        $CrosswalkExchangeLedgerdata['crosswalkExchangeLedger'] = $crosswalkExchangeLedgerValues->toArray();
        $CrosswalkExchangeLedgerdata['total']                   = $total_records;
        return json_encode($CrosswalkExchangeLedgerdata);
    }

    /**
     * Update the specified crosswalk_exchange_ledger in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCrosswalkExchangeLedger(Request $request)
    {
        $callback = $request->callback;
        if (Module::hasAccess("Crosswalk_exchange_ledger", "manage")) {

            $rules = Module::validateRules("Crosswalk_exchange_ledger", $request, true);

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            Module::updateRow("Crosswalk_exchange_ledger", $request, $request->ledger_id);
            return $callback . "(" . json_encode($request) . ")";

        } else {
            return $callback . "(" . json_encode($request) . ")";
        }
    }

    /**
     * Remove the specified crosswalk_exchange_ledger from storage.
     *
     * @param  object  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteCrosswalkExchangeLedger(Request $request)
    {
        $callback = $request->callback;
        if (Module::hasAccess("Crosswalk_exchange_ledger", "delete")) {
            CrosswalkExchangeLedger::find($request->ledger_id)->delete();
            return $callback . "(" . json_encode($request) . ")";
        } else {
            return $callback . "(" . json_encode($request) . ")";
        }
    }
}
