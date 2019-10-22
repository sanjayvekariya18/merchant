<?php

namespace App\Http\Controllers;

use App\Activities;
use App\Helpers\ConnectionManager;
use App\Http\Traits\PermissionTrait;
use App\Module;
use App\ModuleFields;
use App\ModuleFieldTypes;
use DB;
use Illuminate\Http\Request;
use Validator;
use Config;

class ActivitiesController extends PermissionsController
{
    const MERCHANT_TABLE_IDENTITY_TYPE = 8;
    const PORTAL_ADMIN                 = 1;
    const PORTAL_CHECKER               = 3;
    const MERCHANT_ADMIN               = 4;
    const INIT_VALUE                   = 0;

    use PermissionTrait;

    public $show_action  = true;
    public $view_col     = 'merchant_id';
    public $listing_cols = ['activity_id', 'merchant_id', 'user_id', 'action', 'message', 'status', 'ip_address', 'user_timezone', 'user_time', 'date_added', 'user_city'];

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Activity');
        if (strcmp($connectionStatus['type'], "error") == self::INIT_VALUE) {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
    }

    /**
     * Display a listing of the Activities.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $module = Module::get('Activities');

        if (Module::hasAccess($module->id, "access")) {
            return View('activities.index', [
                'show_actions'            => $this->show_action,
                'listing_cols'            => $this->listing_cols,
                'module'                  => $module,
            ]);
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getActivities(Request $request)
    {
        $total_records = self::INIT_VALUE;
        switch ($this->roleId) {
            case self::PORTAL_ADMIN:
                $activitiesDetails = Activities::select('activities.*')->orderBy('date_added', 'desc');
                break;
            case self::PORTAL_CHECKER:
                $activitiesDetails = Activities::select('activities.*')->orderBy('date_added', 'desc')->where('user_id', '!=', self::PORTAL_ADMIN);
                break;
            case self::MERCHANT_ADMIN:
                $activitiesDetails = Activities::select('activities.*')->orderBy('date_added', 'desc')->where('merchant_id', $this->merchantId);
                break;
            default:
                $activitiesDetails = Activities::select('activities.*')->orderBy('date_added', 'desc')->where('user_id', $this->userId);
        }
        if (isset($request->filter)) {
            $searchFiler = $request->filter['filters'][self::INIT_VALUE]['value'];
            if ($searchFiler) {
                $activitiesDetails->where(function ($query) use ($searchFiler, $request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        $query->orWhere('activities.' . $filterValue['field'] . '', 'LIKE', '%' . $searchFiler . '%');
                    }
                });
            }
        }

        $total_records = $total_records + $activitiesDetails->get()->count();
        if (isset($request->take)) {
            $activitiesDetails->offset($request->skip)->limit($request->take);
        }
        $activitiesValues    = $activitiesDetails->get();
        $templateDefineArray = array("Multiselect");
        $fields_popup        = ModuleFields::getModuleFields('Activities');
        $module              = Module::where('name', 'Activities')->orderBy('id', 'desc')->first();
        foreach ($activitiesValues as $key => $value) {
            for ($j = self::INIT_VALUE; $j < count($this->listing_cols); $j++) {
                $col = $this->listing_cols[$j];
                if (isset($value[$col]) && $fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $activitiesValues[$key][$fields_popup[$col]->popup_field_id] = ModuleFields::getKendoFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    } else {
                        $activitiesValues[$key][$fields_popup[$col]->popup_field_name] = ModuleFields::getFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    }

                } else if (isset($value[$col]) && !!$value[$col] && isset($fields_popup[$col]) && !!$fields_popup[$col] && starts_with($fields_popup[$col]->popup_vals, "[")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $TestingEventValues[$key][$fields_popup[$col]->colname] = json_decode($TestingEventValues[$key][$fields_popup[$col]->colname], true);
                    }

                }
            }
        }
        foreach ($activitiesValues as $key => $value) {
            $activitiesValues[$key]->timeDetails = date('m/d/Y h:i A', strtotime($value->date_added)) . " " . Config::get('app.timezone'). ($value->user_timezone != "" ? " [" . $value->user_time . " " . $value->user_timezone . ']' : "");
            $activitiesValues[$key]->humanTiming = PermissionTrait::humanTiming($value->date_added) . " ago";
        }
        $Activitiesdata['activities'] = $activitiesValues->toArray();
        $Activitiesdata['total']      = $total_records;
        return json_encode($Activitiesdata);
    }

    public function getMerchantIdList(Request $request)
    {
        $merchant_idDetails = DB::table("identity_merchant")->select("identity_id", "identity_name")->get()->toArray();
        return json_encode($merchant_idDetails);
    }

    public function getUserIdList(Request $request)
    {
        $user_idDetails = DB::connection("mysqlDynamicConnector")->table("portal_password")->select("user_id", "user_id")->get()->toArray();
        return json_encode($user_idDetails);
    }

    /**
     * Update the specified activities in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateActivities(Request $request)
    {
        $callback = $request->callback;
        if (Module::hasAccess("Activities", "manage")) {

            $rules = Module::validateRules("Activities", $request, true);

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            Module::updateRow("Activities", $request, $request->activity_id);
            return $callback . "(" . json_encode($request) . ")";

        } else {
            return $callback . "(" . json_encode($request) . ")";
        }
    }

    /**
     * Remove the specified activities from storage.
     *
     * @param  object  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteActivities(Request $request)
    {
        $callback = $request->callback;
        if (Module::hasAccess("Activities", "delete")) {
            Activities::find($request->activity_id)->delete();
            return $callback . "(" . json_encode($request) . ")";
        } else {
            return $callback . "(" . json_encode($request) . ")";
        }
    }
}
