<?php

namespace App\Http\Controllers;

use App\Helpers\ConnectionManager;
use App\Http\Controllers\PermissionsController;
use App\Http\Traits\PermissionTrait;
use App\Module;
use App\ModuleFields;
use App\ModuleFieldTypes;
use App\Production_criteria;
use App\Ticket_venue;
use App\VenueCriteriaGroup;
use App\Venue_criteria;
use App\Instances_events;
use DB;
use Illuminate\Http\Request;
use Session;
use URL;
use Validator;
use Carbon\Carbon;

class Venue_criteriaController extends PermissionsController
{
    use PermissionTrait;
    const INIT_VALUE = 0;
    const INDEX_ONE  = 1;
    const INSTANCE_TIME_DIFFERENCE = 30;
    public $show_action  = true;
    public $view_col     = 'venue_id';
    public $listing_cols = ['criteria_id', 'venue_id', 'group_id', 'section', 'row', 'minQuantity'];

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
     * Display a listing of the Venue_criteria.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $module = Module::get('Venue_criteria');

        if (Module::hasAccess($module->id, "access")) {
            return View('venue_criteria.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => $this->listing_cols,
                'module'       => $module,
            ]);
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
    public function criteriaJsonUpload(Request $request)
    {
        if (!empty($request->jsonUrl) || !empty($_FILES["fileToUpload"]["name"][self::INIT_VALUE])) {
            if ($request->jsonUrl != null) {
                $file_headers = @get_headers($request->jsonUrl);
                if (!$file_headers || $file_headers[self::INIT_VALUE] == 'HTTP/1.1 404 Not Found') {
                    return array("type" => "error", "message" => 'Please enter valid URL');
                } else {
                    $criteriaJsonFile  = $request->jsonUrl;
                    $jsonDetailsManage = $this->criteriaJsonDetails($criteriaJsonFile);
                    return $jsonDetailsManage;
                }
            } else {
                $publicDirPath       = public_path(config('app.image_dir_path'));
                $criteriaJsonDirPath = "/criteria_json/";
                $criteriaDirPath     = $publicDirPath . $criteriaJsonDirPath;
                if (!file_exists($criteriaDirPath)) {
                    mkdir($criteriaDirPath, 0777, true);
                }
                $target_dir = $criteriaDirPath;
                $count      = count($_FILES['fileToUpload']['name']);
                $count      = $count - self::INDEX_ONE;
                for ($i = self::INIT_VALUE; $i < $count; $i++) {
                    $temp        = $target_dir;
                    $target_file = $target_dir . basename($_FILES['fileToUpload']['name'][$i]);
                    if (!file_exists($target_file)) {
                        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$i], $target_file)) {
                            "The file " . basename($_FILES["fileToUpload"]["name"][$i]) . " has been uploaded.";
                        }
                    }
                    $criteriaJsonFile  = dirname(URL::to('/')) . "/public/criteria_json/" . $_FILES['fileToUpload']['name'][$i];
                    $jsonDetailsManage = $this->criteriaJsonDetails($criteriaJsonFile);
                }
                return $jsonDetailsManage;
            }
        } else {
            return array("type" => "error", "message" => 'Please upload json or enter URL');
        }
    }
    public function criteriaJsonDetails($criteriaJsonFile)
    {
        $jsonString = file_get_contents($criteriaJsonFile);
        $pattern    = "/\"ThreadName\":\s\"\d*\"\,/";
        $matches    = array();
        preg_match($pattern, $jsonString, $matches);
        if (!empty($matches[self::INIT_VALUE])) {
            $threadName                   = $matches[self::INIT_VALUE];
            $threadNameChange             = str_replace(',', ' ', $threadName);
            $jsonDetailsProperly          = stripslashes($jsonString);
            $criteriaJsonDetails          = str_replace($threadName, $threadNameChange, $jsonDetailsProperly);
            $uploadedCriteriaDetailList[] = json_decode($criteriaJsonDetails, true);
        } else {
            $uploadedCriteriaDetailList[] = json_decode($jsonString, true);
        }
        $rowMessage = $referenceId = $max_quantity = $min_quantity = $brokerId = $purchaseWait = $referencePercent = $internalId = $crtieria_reference = $external_id = $external_reference = '';
        foreach ($uploadedCriteriaDetailList as $criteriaDetailsList) {
            if (isset($criteriaDetailsList)) {
                $venueID       = $criteriaDetailsList['EVENT_DETAILS_LIST'][self::INIT_VALUE]['VenueID'];
                $opponentID    = $criteriaDetailsList['EVENT_DETAILS_LIST'][self::INIT_VALUE]['OpponentID'];
                $production_id = $criteriaDetailsList['EVENT_DETAILS_LIST'][self::INIT_VALUE]['ProductionID'];
                $rangeDetails  = $criteriaDetailsList['criteria'];
                if(isset($criteriaDetailsList['control'])){
                    $threads = $criteriaDetailsList['control'][self::INIT_VALUE]['THREAD_COUNT'];
                    $run_time = $criteriaDetailsList['control'][self::INIT_VALUE]['RUNTIME'];
                    $wait_init = $criteriaDetailsList['control'][self::INIT_VALUE]['PURCHASE_WAIT'];
                    $instancesEventsDetails = array('production_id' => $production_id, 'threads' => $threads,'instance_date' => date("Ymd"),'instance_time' => time(), 'run_time' => $run_time, 'wait_init'=> $wait_init);
                    $this->insertInstancesEventsDetails($instancesEventsDetails);
                }
                $group_id_details = Production_criteria::where('production_id', '=', $production_id)->get()->first();
                Production_criteria::where('production_id', '=', $production_id)->delete();
                if (isset($group_id_details->venue_group_id)) {
                    Venue_criteria::where('group_id', '=', $group_id_details->venue_group_id)->delete();
                }
                $referenceCountValue = self::INIT_VALUE;
                foreach ($rangeDetails as $rangeDetailsValue) {
                    if (!isset($rangeDetailsValue['R'])) {
                        $rowMessage = "Row";
                        continue;
                    }
                    $criteria_row     = $rangeDetailsValue['R'];
                    $criteria_section = $rangeDetailsValue['S'];
                    $group_id_details = VenueCriteriaGroup::where('criteria_section', '=', $criteria_section)->
                        where('criteria_row', '=', $criteria_row)->
                        where('venue_id', '=', $venueID)->get()->first();
                    if (!isset($group_id_details->group_id)) {
                        $group_id = VenueCriteriaGroup::insertGetId(['criteria_section' => $criteria_section, 'criteria_row' => $criteria_row, 'venue_id' => $venueID]);
                    } else {
                        $group_id = $group_id_details->group_id;
                    }
                    if (isset($rangeDetailsValue['ReferenceId'])) {
                        $referenceId = $rangeDetailsValue['ReferenceId'];
                    }
                    if (isset($rangeDetailsValue['MaxQty'])) {
                        $max_quantity = $rangeDetailsValue['MaxQty'];
                    }
                    if (isset($rangeDetailsValue['MinQty'])) {
                        $min_quantity = $rangeDetailsValue['MinQty'];
                    }
                    if (isset($rangeDetailsValue['BrokerId'])) {
                        $brokerId = $rangeDetailsValue['BrokerId'];
                    }
                    if (isset($rangeDetailsValue['PurchaseWait'])) {
                        $purchaseWait = $rangeDetailsValue['PurchaseWait'];
                    }
                    if (isset($rangeDetailsValue['ReferencePercent'])) {
                        $referencePercent = $rangeDetailsValue['ReferencePercent'];
                    }
                    if (isset($rangeDetailsValue['InternalId'])) {
                        $internalIdManage= $criteriaDetailsList['criteria'][self::INIT_VALUE]['InternalId'];
                        if ($referenceCountValue == self::INIT_VALUE) {
                            $internalId         = $rangeDetailsValue['InternalId'];
                            $crtieria_reference = self::INIT_VALUE;
                            $external_id        = $internalId;
                            $external_reference = self::INIT_VALUE;
                        } else {
                            $internalId         = $rangeDetailsValue['InternalId'];
                            $crtieria_reference = Production_criteria::max('criteria_id');
                            $external_id        = $internalId;
                            $external_reference = $internalId - self::INDEX_ONE;
                        }
                    }
                    $criteria_range            = "R:" . $criteria_row . ", S:" . $criteria_section;
                    $productionCriteriaDetails = array('production_id' => $production_id,'criteria_range' => $criteria_range, 'venue_group_id' => $group_id, "wave_id" => self::INIT_VALUE, "criteria_reference_percent" => $referencePercent, "min_quantity" => $min_quantity, "max_quantity" => $max_quantity, "delivery_id" => $rangeDetailsValue['DeliveryId'], "reference_id" => $referenceId, "reference_group_id" => '', "broker_ids" => $brokerId, "purchase_wait" => $purchaseWait, 'referencePercent' => $referencePercent, "MaxPrice" => $rangeDetailsValue['MaxPrice'], "MinPrice" => $rangeDetailsValue['MinPrice'], "internalId" => $internalId, "crtieria_reference" => $crtieria_reference, "external_id" => $external_id , 'external_reference' => $external_reference, "external_reference_percent" => self::INIT_VALUE);
                    $this->insertProductionCriteriaDetails($productionCriteriaDetails);
                    $range            = $rangeDetailsValue['R'];
                    $rangeCommaString = strstr($range, ',', true);
                    $rangeDashString  = strstr($range, '-', true);
                    if ($rangeCommaString != '') {
                        $rangeList = explode(',', $range);
                        foreach ($rangeList as $rangeListValue) {
                            $sectionList = explode(',', $rangeDetailsValue['S']);
                            foreach ($sectionList as $sectionListValue) {

                                $underScoreString = strstr($sectionListValue, '-', true);
                                if ($underScoreString != '') {
                                    $rangeUnderScore                                    = $sectionListValue;
                                    list($rangeUnderScoreFirst, $rangeUnderScoreSecond) = explode('-', $rangeUnderScore);
                                    $rangeUnderScoreValue                               = $rangeUnderScoreSecond - $rangeUnderScoreFirst;
                                    $rangeUnderScoreCountValue                          = $rangeUnderScoreFirst;
                                    for ($rangeUnderScoreStart = self::INIT_VALUE; $rangeUnderScoreStart <= $rangeUnderScoreValue; $rangeUnderScoreStart++) {
                                        $criteriaDetails = array('group_id' => $group_id, "row" => $rangeListValue, "section" => $rangeUnderScoreCountValue, "venueID" => $venueID, "opponentID" => $opponentID, "MaxPrice" => $rangeDetailsValue['MaxPrice'], "MinPrice" => $rangeDetailsValue['MinPrice'], "MinQty" => $rangeDetailsValue['MinQty'], "Filter" => $rangeDetailsValue['Filter'], "AutoBuy" => $rangeDetailsValue['AutoBuy']);
                                        $rangeUnderScoreCountValue++;
                                        $this->insertCriteriaDetails($criteriaDetails);
                                    }

                                } else {
                                    $criteriaDetails = array('group_id' => $group_id, "row" => $rangeListValue, "section" => $sectionListValue, "venueID" => $venueID, "opponentID" => $opponentID, "MaxPrice" => $rangeDetailsValue['MaxPrice'], "MinPrice" => $rangeDetailsValue['MinPrice'], "MinQty" => $rangeDetailsValue['MinQty'], "Filter" => $rangeDetailsValue['Filter'], "AutoBuy" => $rangeDetailsValue['AutoBuy']);
                                }
                                $this->insertCriteriaDetails($criteriaDetails);
                            }
                        }
                    } elseif ($rangeDashString != '') {
                        $range                          = $rangeDetailsValue['R'];
                        list($rangeFirst, $rangeSecond) = explode('-', $range);
                        $rangeValue                     = $rangeSecond - $rangeFirst;
                        $rangeCountValue                = $rangeFirst;
                        for ($rangeStart = self::INIT_VALUE; $rangeStart <= $rangeValue; $rangeStart++) {
                            $sectionList = explode(',', $rangeDetailsValue['S']);
                            foreach ($sectionList as $sectionListValue) {

                                $underScoreString = strstr($sectionListValue, '-', true);
                                if ($underScoreString != '') {
                                    $rangeUnderScore                                    = $sectionListValue;
                                    list($rangeUnderScoreFirst, $rangeUnderScoreSecond) = explode('-', $rangeUnderScore);
                                    $rangeUnderScoreValue                               = $rangeUnderScoreSecond - $rangeUnderScoreFirst;
                                    $rangeUnderScoreCountValue                          = $rangeUnderScoreFirst;
                                    for ($rangeUnderScoreStart = self::INIT_VALUE; $rangeUnderScoreStart <= $rangeUnderScoreValue; $rangeUnderScoreStart++) {
                                        $criteriaDetails = array('group_id' => $group_id, "row" => $rangeCountValue, "section" => $rangeUnderScoreCountValue, "venueID" => $venueID, "opponentID" => $opponentID, "MaxPrice" => $rangeDetailsValue['MaxPrice'], "MinPrice" => $rangeDetailsValue['MinPrice'], "MinQty" => $rangeDetailsValue['MinQty'], "Filter" => $rangeDetailsValue['Filter'], "AutoBuy" => $rangeDetailsValue['AutoBuy']);
                                        $rangeUnderScoreCountValue++;
                                    }
                                    $this->insertCriteriaDetails($criteriaDetails);

                                } else {
                                    $criteriaDetails = array('group_id' => $group_id, "row" => $rangeCountValue, "section" => $sectionListValue, "venueID" => $venueID, "opponentID" => $opponentID, "MaxPrice" => $rangeDetailsValue['MaxPrice'], "MinPrice" => $rangeDetailsValue['MinPrice'], "MinQty" => $rangeDetailsValue['MinQty'], "Filter" => $rangeDetailsValue['Filter'], "AutoBuy" => $rangeDetailsValue['AutoBuy']);
                                }
                            }
                            $this->insertCriteriaDetails($criteriaDetails);
                            $rangeCountValue++;
                        }
                    } else {
                        $rangeCountValue = $rangeDetailsValue['R'];
                        $sectionList     = explode(',', $rangeDetailsValue['S']);
                        foreach ($sectionList as $sectionListValue) {

                            $underScoreString = strstr($sectionListValue, '-', true);
                            if ($underScoreString != '') {
                                $rangeUnderScore                                    = $sectionListValue;
                                list($rangeUnderScoreFirst, $rangeUnderScoreSecond) = explode('-', $rangeUnderScore);
                                $rangeUnderScoreValue                               = $rangeUnderScoreSecond - $rangeUnderScoreFirst;
                                $rangeUnderScoreCountValue                          = $rangeUnderScoreFirst;
                                for ($rangeUnderScoreStart = self::INIT_VALUE; $rangeUnderScoreStart <= $rangeUnderScoreValue; $rangeUnderScoreStart++) {
                                    $criteriaDetails = array('group_id' => $group_id, "row" => $rangeCountValue, "section" => $rangeUnderScoreCountValue, "venueID" => $venueID, "opponentID" => $opponentID, "MaxPrice" => $rangeDetailsValue['MaxPrice'], "MinPrice" => $rangeDetailsValue['MinPrice'], "MinQty" => $rangeDetailsValue['MinQty'], "Filter" => $rangeDetailsValue['Filter'], "AutoBuy" => $rangeDetailsValue['AutoBuy']);
                                    $rangeUnderScoreCountValue++;
                                }
                                $this->insertCriteriaDetails($criteriaDetails);

                            } else {
                                $criteriaDetails = array('group_id' => $group_id, "row" => $rangeCountValue, "section" => $sectionListValue, "venueID" => $venueID, "opponentID" => $opponentID, "MaxPrice" => $rangeDetailsValue['MaxPrice'], "MinPrice" => $rangeDetailsValue['MinPrice'], "MinQty" => $rangeDetailsValue['MinQty'], "Filter" => $rangeDetailsValue['Filter'], "AutoBuy" => $rangeDetailsValue['AutoBuy']);
                            }
                        }
                        $this->insertCriteriaDetails($criteriaDetails);
                    }
                    $referenceCountValue++;
                }
                return array("type" => "success", "message" => 'Json details Uploaded' . " " . $rowMessage);
            } else {
                return array("type" => "error", "message" => 'Json Details Not Properly');
            }
        }
    }
    public function insertProductionCriteriaDetails($productionCriteriaDetails)
    {
        $productionCriteriaDetailsList = Production_criteria::where('venue_group_id', '=', $productionCriteriaDetails['venue_group_id'])->where('wave_id', '=', $productionCriteriaDetails['wave_id'])->get()->first();
        if (!isset($productionCriteriaDetailsList->venue_group_id)) {
            $criteria_id = Production_criteria::insertGetId([
                'production_id'              => $productionCriteriaDetails['production_id'],
                'criteria_range'             => $productionCriteriaDetails['criteria_range'],
                'venue_group_id'             => $productionCriteriaDetails['venue_group_id'],
                'wave_id'                    => $productionCriteriaDetails['wave_id'],
                'criteria_reference_id'      => $productionCriteriaDetails['crtieria_reference'],
                'criteria_reference_percent' => $productionCriteriaDetails['criteria_reference_percent'],
                'min_quantity'               => $productionCriteriaDetails['min_quantity'],
                'max_quantity'               => $productionCriteriaDetails['max_quantity'],
                'delivery_id'                => $productionCriteriaDetails['delivery_id'],
                'external_id'                => $productionCriteriaDetails['external_id'],
                'external_reference_id'      => $productionCriteriaDetails['external_reference'],
                'external_reference_percent' => $productionCriteriaDetails['external_reference_percent'],
                'broker_ids'                 => $productionCriteriaDetails['broker_ids'],
                'purchase_wait'              => $productionCriteriaDetails['purchase_wait'],
                'min_price'                  => $productionCriteriaDetails['MinPrice'],
                'max_price'                  => $productionCriteriaDetails['MaxPrice']]);
        } else {
            Production_criteria::where('venue_group_id', $productionCriteriaDetailsList->venue_group_id)->update([
                'production_id'              => $productionCriteriaDetails['production_id'],
                'criteria_range'             => $productionCriteriaDetails['criteria_range'],
                'min_quantity'               => $productionCriteriaDetails['min_quantity'],
                'max_quantity'               => $productionCriteriaDetails['max_quantity'],
                'delivery_id'                => $productionCriteriaDetails['delivery_id'],
                'external_reference_percent' => $productionCriteriaDetails['external_reference_percent'],
                'broker_ids'                 => $productionCriteriaDetails['broker_ids'],
                'purchase_wait'              => $productionCriteriaDetails['purchase_wait'],
                'min_price'                  => $productionCriteriaDetails['MinPrice'],
                'max_price'                  => $productionCriteriaDetails['MaxPrice']]);
        }
    }
    public function insertInstancesEventsDetails($instancesEventsDetails)
    {
        $instancesEventsDetailsList = Instances_events::where('production_id', '=', $instancesEventsDetails['production_id'])->get()->first();
        if (!isset($instancesEventsDetailsList->id)) {
            $id = Instances_events::insertGetId([
                'production_id' => $instancesEventsDetails['production_id'],
                'instance_date' => $instancesEventsDetails['instance_date'],
                'instance_time' => $instancesEventsDetails['instance_time'],
                'threads'       => $instancesEventsDetails['threads'],
                'run_time'      => $instancesEventsDetails['run_time'],
                'wait_init'     => $instancesEventsDetails['wait_init']]);
        } else {
            $instance_added_datetime    = json_decode(PermissionTrait::covertToLocalTz($instancesEventsDetails['instance_time']));
            $instance_datetime          = json_decode(PermissionTrait::covertToLocalTz($instancesEventsDetailsList->instance_time));
            $instance_datetime          = strtotime($instance_datetime->time);
            $instance_added_datetime    = strtotime($instance_added_datetime->time);
            $timeDiffrence              = $instance_datetime - $instance_added_datetime;
            $instanceTimeDiffrence = abs($timeDiffrence);
            if($instanceTimeDiffrence < self::INSTANCE_TIME_DIFFERENCE){
                Instances_events::where('production_id', $instancesEventsDetailsList->production_id)->update([
                    'instance_date' => $instancesEventsDetails['instance_date'],
                    'instance_time' => $instancesEventsDetails['instance_time'],
                    'threads'       => $instancesEventsDetails['threads'],
                    'run_time'      => $instancesEventsDetails['run_time'],
                    'wait_init'     => $instancesEventsDetails['wait_init']]);
            }
        }
    }
    public function insertCriteriaDetails($criteriaDetails)
    {
        $criteriaDetailsList = Venue_criteria::where('venue_id', '=', $criteriaDetails['venueID'])->
            where('section', '=', $criteriaDetails['section'])->
            where('row', '=', $criteriaDetails['row'])->get()->first();
        if (!isset($criteriaDetailsList->criteria_id)) {
            $criteria_id = Venue_criteria::insertGetId([
                'venue_id'    => $criteriaDetails['venueID'],
                'group_id'    => $criteriaDetails['group_id'],
                'section'     => $criteriaDetails['section'],
                'row'         => $criteriaDetails['row'],
                'minQuantity' => $criteriaDetails['MinQty']]);
        }
    }
    public function criteriaExceptionsDetails(Request $request)
    {
        $criteriaExceptionsDetails = array();
        $total_records             = self::INIT_VALUE;
        if ($request->jsonUrl != null) {
            $file_headers = @get_headers($request->jsonUrl);
            if (!$file_headers || $file_headers[self::INIT_VALUE] == 'HTTP/1.1 404 Not Found') {
                return array("type" => "error", "message" => 'Please enter valid URL');
            } else {
                $criteriaJsonFile = $request->jsonUrl;
            }
        } else {
            $criteriaJsonFile    = $request->fileToUpload;
            $publicDirPath       = public_path(config('app.image_dir_path'));
            $criteriaJsonDirPath = "/criteria_json/";
            $criteriaDirPath     = $publicDirPath . $criteriaJsonDirPath;
            $fileName            = $criteriaJsonFile;
            $criteriaJsonFile    = dirname(URL::to('/')) . "/public/criteria_json/" . $fileName;
        }
        $jsonString = file_get_contents($criteriaJsonFile);
        $pattern    = "/\"ThreadName\":\s\"\d*\"\,/";
        $matches    = array();
        preg_match($pattern, $jsonString, $matches);
        if (!empty($matches[self::INIT_VALUE])) {
            $threadName                   = $matches[self::INIT_VALUE];
            $threadNameChange             = str_replace(',', ' ', $threadName);
            $jsonDetailsProperly          = stripslashes($jsonString);
            $criteriaJsonDetails          = str_replace($threadName, $threadNameChange, $jsonDetailsProperly);
            $uploadedCriteriaDetailList[] = json_decode($criteriaJsonDetails, true);
        } else {
            $uploadedCriteriaDetailList[] = json_decode($jsonString, true);
        }
        foreach ($uploadedCriteriaDetailList as $criteriaDetailsList) {
            if (isset($criteriaDetailsList)) {
                $venueID       = $criteriaDetailsList['EVENT_DETAILS_LIST'][self::INIT_VALUE]['VenueID'];
                $opponentID    = $criteriaDetailsList['EVENT_DETAILS_LIST'][self::INIT_VALUE]['OpponentID'];
                $production_id = $criteriaDetailsList['EVENT_DETAILS_LIST'][self::INIT_VALUE]['ProductionID'];
                $rangeDetails  = $criteriaDetailsList['criteria'];
                foreach ($rangeDetails as $rangeDetailsValue) {
                    if (!isset($rangeDetailsValue['R'])) {
                        $criteria_row = $referenceId = $max_quantity = $min_quantity = $brokerId = $purchaseWait = $referencePercent = '';
                        if (isset($rangeDetailsValue['ReferenceId'])) {
                            $referenceId = $rangeDetailsValue['ReferenceId'];
                        }
                        if (isset($rangeDetailsValue['MaxQty'])) {
                            $max_quantity = $rangeDetailsValue['MaxQty'];
                        }
                        if (isset($rangeDetailsValue['MinQty'])) {
                            $min_quantity = $rangeDetailsValue['MinQty'];
                        }
                        if (isset($rangeDetailsValue['BrokerId'])) {
                            $brokerId = $rangeDetailsValue['BrokerId'];
                        }
                        if (isset($rangeDetailsValue['PurchaseWait'])) {
                            $purchaseWait = $rangeDetailsValue['PurchaseWait'];
                        }
                        if (isset($rangeDetailsValue['ReferencePercent'])) {
                            $referencePercent = $rangeDetailsValue['ReferencePercent'];
                        }
                        $criteria_section            = $rangeDetailsValue['S'];
                        $criteriaExceptionsDetails[] = array('files' => $criteriaJsonFile, "row" => $criteria_row, "section" => $criteria_section, "venueID" => $venueID, "opponentID" => $opponentID, "MaxPrice" => $rangeDetailsValue['MaxPrice'], "MinPrice" => $rangeDetailsValue['MinPrice'], "MinQty" => $rangeDetailsValue['MinQty'], "Filter" => $rangeDetailsValue['Filter'], "DeliveryId" => $rangeDetailsValue['DeliveryId'], "AutoBuy" => $rangeDetailsValue['AutoBuy'], 'production_id' => $production_id, "min_quantity" => $min_quantity, "max_quantity" => $max_quantity, "delivery_id" => $rangeDetailsValue['DeliveryId'], "reference_id" => $referenceId, "broker_ids" => $brokerId, "purchase_wait" => $purchaseWait, 'referencePercent' => $referencePercent);
                    }
                }
            }
        }
        $total_records                                           = count($criteriaExceptionsDetails);
        $criteria_exceptions_data['criteria_exceptions_details'] = $criteriaExceptionsDetails;
        $criteria_exceptions_data['total']                       = $total_records;
        return json_encode($criteria_exceptions_data);

    }
    public function getVenue_criteria(Request $request)
    {
        $total_records = self::INIT_VALUE;

        $Venue_criteriaDetails = Venue_criteria::
            select('venue_criteria.*');

        if (isset($request->filter)) {
            $searchFilter = $request->filter['filters'][self::INIT_VALUE]['value'];
            if ($searchFilter) {
                $Venue_criteriaDetails->where(function ($query) use ($searchFilter, $request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        $query->orWhere('venue_criteria.' . $filterValue['field'] . '', 'LIKE', '%' . $searchFilter . '%');
                    }
                });
            }
        }

        $total_records = $total_records + $Venue_criteriaDetails->get()->count();
        if (isset($request->take)) {
            $Venue_criteriaDetails->offset($request->skip)->limit($request->take);
        }
        $Venue_criteriaValues = $Venue_criteriaDetails->get();
        $templateDefineArray  = array("Multiselect");
        $fields_popup         = ModuleFields::getModuleFields('Venue_criteria');
        $module               = Module::where('name', 'Venue_criteria')->first();
        foreach ($Venue_criteriaValues as $key => $value) {
            for ($j = self::INIT_VALUE; $j < count($this->listing_cols); $j++) {
                $col = $this->listing_cols[$j];
                if (isset($value[$col]) && $fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $Venue_criteriaValues[$key][$fields_popup[$col]->popup_field_id] = ModuleFields::getKendoFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    } else {
                        $Venue_criteriaValues[$key][$fields_popup[$col]->popup_field_name] = ModuleFields::getFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    }

                } else if (isset($value[$col]) && !!$value[$col] && isset($fields_popup[$col]) && !!$fields_popup[$col] && starts_with($fields_popup[$col]->popup_vals, "[")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $TestingEventValues[$key][$fields_popup[$col]->colname] = json_decode($TestingEventValues[$key][$fields_popup[$col]->colname], true);
                    }

                }
            }
        }
        $Venue_criteriaValues                  = $Venue_criteriaValues->toArray();
        $Venue_criteria_data['Venue_criteria'] = array_values($Venue_criteriaValues);
        $Venue_criteria_data['total']          = $total_records;
        return json_encode($Venue_criteria_data);
    }

    public function getvenue_idList(Request $request)
    {
        $venue_idDetails = DB::connection("mysqlDynamicConnector")->table("venue")->select("venue_id", "venue_name")->get()->toArray();
        return json_encode($venue_idDetails);
    }

    /**
     * Update the specified venue_criteria in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateVenue_criteria(Request $request)
    {
        $callback = $request->callback;
        if (Module::hasAccess("Venue_criteria", "manage")) {

            $rules = Module::validateRules("Venue_criteria", $request, true);

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            Module::updateRow("Venue_criteria", $request, $request->criteria_id);
            return $callback . "(" . json_encode($request) . ")";

        } else {
            return $callback . "(" . json_encode($request) . ")";
        }
    }

    /**
     * Remove the specified venue_criteria from storage.
     *
     * @param  object  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteVenue_criteria(Request $request)
    {
        $callback = $request->callback;
        if (Module::hasAccess("Venue_criteria", "delete")) {
            Venue_criteria::find($request->criteria_id)->delete();
            return $callback . "(" . json_encode($request) . ")";
        } else {
            return $callback . "(" . json_encode($request) . ")";
        }
    }

    public function getVenueName(Request $request)
    {
        try {
            $venueDetails = Ticket_venue::
                select('venue_name')
                ->where('venue_id', $request->venue_id)
                ->get()->first();
            return $venueDetails;
        } catch (\Exception $e) {
            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return (array("error" => $exceptionMessage));
        }
    }
}
