<?php
namespace App\Http\Controllers;

use App\Helpers\DomScrapeStatus;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Http\Traits\PermissionTrait;
use App\Helpers\DateTimeFormatter;
use App\Identity_table_type;
use App\Regex_class_history;
use App\Regex_class_override;
use App\RegexField;
use App\Regex_field_specific;
use App\Regex_map_category;
use App\Regex_reference_node;
use App\Scrape_status_history;
use App\CheckedNodeGroups;
use App\RegexMapTargetFields;
use App\RegexTableAccess;
use App\RegexReferenceValue;
use App\Asset;
use App\Social;
use App\Identity_social;
use App\RegexPrimitive;
use App\PrimitiveHistory;
use App\RegexSplit;
use App\RegexSplitPrimitive;
USE App\Crosswalk_position;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Schema;
use Session;
use Redirect;
use Sunra\PhpSimple\HtmlDomParser;
use Analog\AnalogHelper as Debug;



include app_path() . '/Http/Controllers/simpleHtmlDom/HtmlDomParser.php';
include_once base_path('vendor/').'analog-helper/AnalogHelper.php';

class HtmldomController extends PermissionsController
{
    const NULL_VALUE      = '';
    const NODE_TAG        = 'tag';
    const NODE_FIELD      = 'field';
    const NODE_VALUE      = 'value';
    const NODE_CHILD      = 'child';
    const HAS_CHILDREN    = 'hasChildren';
    const NODE_ID         = 'nodeId';
    const PARENT_NODE_ID  = 'parentNodeId';
    const ANCHOR_TAG      = 'a';
    const INITIAL_CHECKED = 'selected';
    const ANCHOR_HREF     = 'href="';
    const COMMA           = '"';
    const NODE_INDEX      = 'nodeIndex';
    const PARENT_PATH     = 'path';
    const PATH_SLASH      = '/';
    const URL_REGEX       = "/(((https?:\/\/)|(www\.))[^\s]+)/";
    const HTMLDOM_DIR     = "/htmldom/";
    const IMAGE_PATTERN   = '/background-image:[ ]?url\(["|\']?(.*?\.(?:png|jpg|jpeg|gif))/';
    const DOM_CHANGES     = 'dom_changes';
    const TRANSFORM_APPLY = 'transform_apply';
    const CROSSWALK_PREFIX  = 'Crosswalk_';
    const SOCIAL_SCHEMA_NAME = 'social';
    const MATCH_REFERENCE = 4;
    const PRIMITIVE_GROUP_ID = 6;
    const EXPAND_TRUE     = 1;


    private $currentId;

    private $parentId;

    private $mainArray;

    private $skipTag;

    private $parentNodePath;

    private $domScrapeStatus;

    private $diffChanges;

    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();
        $connectionStatus = ConnectionManager::setDbConfig('Htmldom');
        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
        $this->currentId      = 0;
        $this->parentId       = null;
        $this->parentNodePath = self::NULL_VALUE;
        $this->mainArray      = [];
        $this->skipTag        = array("br", "b", "comment", "noscript", "script");
        $this->diffChanges    = 0;
        $this->domScrapeStatus = new DomScrapeStatus();
    }

    public function index()
    {   
        $hostname = \Request::getHost();
        return view('htmldom.index',compact("hostname"));
    }

    public function updateHtmlDomClassList(Request $request)
    {
        $regex_class_override   = new Regex_class_override;
        $htmlClassChangeDetails = Regex_class_override::where("node_id", $request->node_id)->where('table_id', $request->table_id)
            ->where('entry_id', $request->identityId)
            ->first();
        if (!isset($htmlClassChangeDetails->node_id)) {
            $regex_class_override->table_id     = $request->table_id;
            $regex_class_override->entry_id     = $request->identityId;
            $regex_class_override->class_data   = $request->class_data;
            $regex_class_override->node_id      = $request->node_id;
            $regex_class_override->class_change = $request->class_change;
            $regex_class_override->save();
            $override_id = $regex_class_override->override_id;
        } else {
            $override_id = $htmlClassChangeDetails->override_id;
            DB::table('regex_class_override')
                ->where('override_id', '=', $override_id)
                ->update(array('class_change' => $request->class_change));
        }
        $htmlClassChangeHistoryDetails = Regex_class_history::where("override_id", $override_id)->where('class_change', $request->class_change)->first();
        $htmlClassChangeHistoryList  = Regex_class_history::where('override_id', '=', $override_id)->get();
        $htmlClassChangeHistoryCount = count($htmlClassChangeHistoryList);
        $historyVersion              = $htmlClassChangeHistoryCount + 1;
        $regex_class_history         = new Regex_class_history;
        if (!isset($htmlClassChangeHistoryDetails->history_id)) {
            $regex_class_history->override_id    = $override_id;
            $regex_class_history->class_change   = $request->class_change;
            $regex_class_history->change_version = $historyVersion;
            $regex_class_history->change_date = date('Ymd');
            $regex_class_history->change_time = date('H:i:s');
            $regex_class_history->owner = $this->userId;
            $regex_class_history->save();
        }
    }
    
    public function htmlDomHistoryListList(Request $request)
    {
        $nodeId = $request->node_id;
        $identityId = $request->identity_id;
        $identityTableId = $request->identity_table_id;
        $htmlDomHistoryList = Regex_class_history::select('regex_class_history.*',
            'regex_class_override.node_id', 
            'portal_password.username as owner_name')
            ->join('regex_class_override', 'regex_class_override.override_id', 'regex_class_history.override_id')
            ->join('portal_password','portal_password.user_id','regex_class_history.owner')
            ->where('regex_class_override.node_id', $nodeId)
            ->where('regex_class_override.entry_id', $identityId)
            ->where('regex_class_override.table_id', $identityTableId)
            ->get();
        foreach ($htmlDomHistoryList as $key => $htmlDomHistoryListValue) {
            $htmlDomHistoryList[$key]->domStatus = $this->domScrapeStatus->getManualStatus();
            if($htmlDomHistoryListValue->change_date != 0) {
                $dateTime = $htmlDomHistoryListValue->change_date.' '.$htmlDomHistoryListValue->change_time;
                $timeStamp = strtotime($dateTime);
                $localDateTime = PermissionTrait::covertToLocalTz($timeStamp);
                $dateTimeValue = json_decode($localDateTime);
                $htmlDomHistoryList[$key]->change_time = $dateTimeValue->time;
                $htmlDomHistoryList[$key]->change_date = $dateTimeValue->date;
            }
            else {
                $htmlDomHistoryList[$key]->change_time = '';
                $htmlDomHistoryList[$key]->change_date = '';
            }
        }
        $htmlDomHistoryListDetails = $htmlDomHistoryList->toArray();
        return $htmlDomHistoryListDetails;
    }

    public function getRegexFields($regexGroup)
    {
        $regexFieldArray = [];
        if ($regexGroup == 2) {
            $regexFields = RegexField::select('field_name')->get();
            foreach ($regexFields as $fieldValue) {
                array_push($regexFieldArray, $fieldValue->field_name);
            }
        }
        return $regexFieldArray;
    }

    public function getSocialRegexPatterns($labelId)
    {
        $regexPatterns = Regex_map_category::select(
            'regex_map_category.ref_class',
            'regex_map_category.ref_column',
            'regex_map_category.ref_table',
            'regex_map_category.ref_id',
            'regex_pattern.pattern_id',
            'regex_pattern.pattern',
            'regex_field.field_name')
            ->join('regex_pattern', 'regex_pattern.pattern_id', 'regex_map_category.regex_pattern_id')
            ->join('regex_field', 'regex_field.field_id', 'regex_map_category.regex_field_id')
            ->where('regex_map_category.regex_name_id', $labelId)
            ->get();
        return $regexPatterns;
    }

    public function getPrimitiveRegexPatterns()
    {
        $primitivePatterns = RegexPrimitive::
                                    join('regex_type', 'regex_primitive.type_id', 'regex_type.type_id')
                                    ->get();

        return $primitivePatterns;
    }

    public function getwebsiteUrlJson($website_url)
    {
        $website_url_json             = str_replace('.', '-', $website_url);
        $website_url_underscore       = str_replace('/', '_', $website_url_json);
        return $website_url_json_file = str_replace('#', '', $website_url_underscore);
    }

    public function getGroupPatterns(Request $request)
    {
        $groupId = $request->group_id;
        if ($groupId == 1) {
            $regexGroupPatterns = $this->getSocialRegexPatterns($groupId);
            return json_encode($regexGroupPatterns);
        } else if ($groupId == 2) {
            $regexGroupPatterns = $this->getRegexFields($groupId);
            return json_encode($regexGroupPatterns);
        } else if ($groupId == self::MATCH_REFERENCE) {
            return $this->getMatchReferenceClass();
        } else if ($groupId == self::PRIMITIVE_GROUP_ID) {
            $primitiveRegexPatterns = $this->getPrimitiveRegexPatterns();
            return json_encode($primitiveRegexPatterns);
        } else {
            $regexPatterns = $this->getRegexPatterns($groupId);
            return json_encode($regexPatterns);
        }
    }

    public function getRegexPatterns($categoryId)
    {
        $regexPatterns = Regex_map_category::select(
            'regex_map_category.ref_class',
            'regex_map_category.ref_column',
            'regex_map_category.ref_table',
            'regex_map_category.ref_id',
            'regex_map_category.regex_name_id',
            'regex_pattern.pattern_id',
            'regex_pattern.pattern')
            ->join('regex_pattern', 'regex_pattern.pattern_id', 'regex_map_category.regex_pattern_id')
            ->where('regex_map_category.ref_class', '!=', '')
            ->where('regex_map_category.regex_name_id', $categoryId)
            ->get();
        return $regexPatterns;
    }

    public function getMatchReferenceClass() {
        $careerReferenceData = $this->getRegexPatterns(self::MATCH_REFERENCE);
        $careerReferenceClass = [];
        foreach ($careerReferenceData as $referenceValue) {
            array_push($careerReferenceClass, $referenceValue->ref_class);
        }
        return json_encode($careerReferenceClass);
    }

    public function saveTempReferenceData(Request $requestData) {
        $referenceData = json_decode($requestData->reference_data);
        $referenceNodeData = [];
        $referenceValueData = [];
        foreach ($referenceData as $nodeData) {
            if (!in_array($nodeData->reference_value, $referenceValueData)) {
                $referenceValueData[] = $nodeData->reference_value;
                $referenceDataCheck = RegexReferenceValue::select('regex_reference_value.*')
                    ->where('reference_value', $nodeData->reference_value)
                    ->get()->first();
                if(!$referenceDataCheck) {
                    $referenceNodeData[] = [
                        'user_id'   => $this->userId,
                        'node_id'   =>  $nodeData->node_id,
                        'reference_key' => $nodeData->reference_key,
                        'reference_value' => $nodeData->reference_value
                    ];
                }
            }
        }
        RegexReferenceValue::insert($referenceNodeData);
    }

    public function saveCrosswalkData(Request $requestData) {
        try{
            $crosswalkData = json_decode($requestData->crosswalk_data);
            foreach ($crosswalkData as $nodeData) {
                $crosswalkSchema = self::CROSSWALK_PREFIX.$nodeData->reference_key;
                $crosswalkDataCheck = DB::table($crosswalkSchema)->where('name', $nodeData->reference_value)
                        ->get()->first();
                if(!$crosswalkDataCheck) {
                    $crosswalkNodeData = [
                        'name' => $nodeData->reference_value,
                        'lookup_id' => 0
                    ];
                    DB::table($crosswalkSchema)->insert($crosswalkNodeData);
                }
            }
            
            return json_encode(array('type' => "success",'message' => "Reference data stored"));
        }catch(Exception $e){
            return json_encode(array('type' => "error",'message' => $e->getMessage()));
        }
        
    }

    public function getMatchReferenceId($referenceObject, $domNodeValue) {
        $referenceTable = $referenceObject->ref_table;
        $referenceColumn = $referenceObject->ref_column;
        $referenceClassData = DB::table($referenceTable)
            ->where($referenceColumn, $domNodeValue)
            ->get()->first();
        if ($referenceClassData) {
            $primaryColumnQuery = "SHOW KEYS FROM ".$referenceTable. " WHERE Key_name = 'PRIMARY'";
            $resultsData = DB::select(DB::raw($primaryColumnQuery));
            $columnName = $resultsData[0]->Column_name;
            $referenceId = $referenceClassData->{$columnName};
            return $referenceId;
        }
    }

    public function getManualOverrideClass($identityTableId, $identityId)
    {
        $regexOverrideClass = Regex_class_override::select(
            'regex_class_override.class_data',
            'regex_class_override.node_id',
            'regex_class_override.class_change')
            ->where('regex_class_override.table_id', $identityTableId)
            ->where('regex_class_override.entry_id', $identityId)
            ->get();
        return $regexOverrideClass;
    }

    public function getNodeReferenceInfo($identityTableId, $identityId) {
        $domNodeReference = Regex_reference_node::select('regex_reference_node.node_id',
            'regex_reference_node.reference_table',
            'regex_reference_node.reference_column',
            'regex_reference_node.reference_id')
            ->where('regex_reference_node.identity_table_id', $identityTableId)
            ->where('regex_reference_node.identity_id', $identityId)
            ->get();
        return $domNodeReference;
    }

    public function saveCareerInfo($referenceObject, $domNodeValue) {
        $referenceTable = $referenceObject->ref_table;
        $referenceColumn = $referenceObject->ref_column;
        $referenceClassData = DB::table($referenceTable)
            ->where($referenceColumn, $domNodeValue)
            ->get()->first();

        if (!$referenceClassData) {
            $referenceId = DB::table($referenceTable)->insertGetId(
                [$referenceColumn => $domNodeValue]
            );
        }
        else {
            $primaryColumnQuery = "SHOW KEYS FROM ".$referenceTable. " WHERE Key_name = 'PRIMARY'";
            $resultsData = DB::select(DB::raw($primaryColumnQuery));
            $columnName = $resultsData[0]->Column_name;
            $referenceId = $referenceClassData->{$columnName};
        }
        return $referenceId;
    }

    public function getLeafLevelValue($childrenObject, $childCount, $parentNodeId, $parentNodePath, $regexGroup, $domainUrl, $domainWithoutSlash, $regexLabel, $identityTableId, $identityTableName, $identityId, $website_url, $overrideNodeArray, $referenceNodeArray)
    {
        $regexData           = $this->getRegexFields($regexGroup);
        $socialRegexPatterns = [];
        $primitiveRegexPatterns = [];
        if ($regexGroup != '' && $regexGroup != self::PRIMITIVE_GROUP_ID) {            
            $socialRegexPatterns = $this->getSocialRegexPatterns($regexGroup);
        }else if ($regexGroup != '' && $regexGroup == self::PRIMITIVE_GROUP_ID) {            
            $primitiveRegexPatterns = $this->getPrimitiveRegexPatterns();
        }
        $regexPatterns       = $this->getRegexPatterns(1);
        $manualClassOverride = $this->getManualOverrideClass($identityTableId, $identityId);
        $nodeReferenceInfo = $this->getNodeReferenceInfo($identityTableId, $identityId);
        $careerPatterns = $this->getRegexPatterns(self::MATCH_REFERENCE);

        for ($initChild = 0; $initChild < $childCount; $initChild++) {
            unset($referenceClass);
            unset($referenceColumn);
            unset($referenceId);
            unset($referenceTable);
            $socialUrlExist = false;
            $referenceClassExist = false;
            $primitiveExist = false;
            $subChildren    = $childrenObject->children($initChild);
            $subChildCount  = count($subChildren->children());
            if (!in_array($subChildren->tag, $this->skipTag)) {
                $this->mainArray[$this->currentId][self::NODE_TAG] = $subChildren->tag;
                if (isset($subChildren->id)) {
                    $this->mainArray[$this->currentId][self::NODE_FIELD] = $subChildren->id;
                    $nodeField                                           = $subChildren->id;
                    $this->mainArray[$this->currentId]['class_data']     = $subChildren->id;
                } else if (isset($subChildren->class)) {
                    $this->mainArray[$this->currentId][self::NODE_FIELD] = $subChildren->class;
                    $nodeField                                           = $subChildren->class;
                    $this->mainArray[$this->currentId]['class_data']     = $subChildren->class;
                } else {
                    $this->mainArray[$this->currentId][self::NODE_FIELD] = self::NULL_VALUE;
                    $nodeField                                           = self::NULL_VALUE;
                    $this->mainArray[$this->currentId]['class_data']     = self::NULL_VALUE;
                }

                if ($subChildren->tag == self::ANCHOR_TAG) {
                    $anchorNodeValue = $this->getNodeValue($subChildren, $subChildren->children());
                    if (isset($subChildren->href)) {
                        if (preg_match(self::URL_REGEX, $subChildren->href)) {
                            $domNodeValue = $subChildren->href;
                        } else {
                            $splitNodeValue = explode(self::PATH_SLASH, $subChildren->href);
                            if ($splitNodeValue[0] == self::NULL_VALUE) {
                                $domNodeValue = $domainWithoutSlash . $subChildren->href;
                            } else {
                                $domNodeValue = $domainUrl . $subChildren->href;
                            }
                        }
                        $this->mainArray[$this->currentId][self::NODE_VALUE] = $domNodeValue;
                    } else if(!empty($anchorNodeValue)) {
                        $this->mainArray[$this->currentId][self::NODE_VALUE] = $anchorNodeValue;
                        $domNodeValue = $anchorNodeValue;
                    } else {
                        $this->mainArray[$this->currentId][self::NODE_VALUE] = self::NULL_VALUE;
                        $domNodeValue                                        = self::NULL_VALUE;
                    }
                    $socialUrl = self::ANCHOR_HREF . $subChildren->href . self::COMMA;
                    if (count($socialRegexPatterns) > 0 && $regexGroup == 1) {
                        foreach ($socialRegexPatterns as $socialRegex) {
                            if (preg_match($socialRegex->pattern, $socialUrl, $match)) {
                                $socialUrlExist  = true;
                                $referenceClass  = $socialRegex->ref_class;
                                $referenceColumn = $socialRegex->ref_column;
                                $referenceTable  = $socialRegex->ref_table;
                                $referenceId     = $socialRegex->ref_id;
                            }
                        }
                    } else if ($regexPatterns) {
                        foreach ($regexPatterns as $socialRegex) {
                            if (preg_match($socialRegex->pattern, $socialUrl, $match)) {
                                $referenceClass  = $socialRegex->ref_class;
                                $referenceColumn = $socialRegex->ref_column;
                                $referenceTable  = $socialRegex->ref_table;
                                $referenceId     = $socialRegex->ref_id;
                            }
                        }
                    }
                } else if ($subChildren->tag == 'img') {
                    if (isset($subChildren->src)) {
                        if (preg_match(self::URL_REGEX, $subChildren->src)) {
                            $domNodeValue = $subChildren->src;
                        } else {
                            $splitNodeValue = explode(self::PATH_SLASH, $subChildren->src);
                            if ($splitNodeValue[0] == self::NULL_VALUE) {
                                $domNodeValue = $domainWithoutSlash . $subChildren->src;
                            } else {
                                $domNodeValue = $domainUrl . $subChildren->src;
                            }
                        }
                        $this->mainArray[$this->currentId][self::NODE_VALUE] = $domNodeValue;
                    } else {
                        $this->mainArray[$this->currentId][self::NODE_VALUE] = self::NULL_VALUE;
                        $domNodeValue                                        = self::NULL_VALUE;
                    }
                } else {
                    $nodeStyle = $subChildren->style;
                    preg_match(self::IMAGE_PATTERN, $nodeStyle, $matchData);
                    if (isset($matchData) && count($matchData) > 0) {
                        $styleImageValue = $matchData[1];
                        if (preg_match(self::URL_REGEX, $styleImageValue)) {
                            $domNodeValue = $styleImageValue;
                        } else {
                            $splitNodeValue = explode(self::PATH_SLASH, $styleImageValue);
                            if ($splitNodeValue[0] == self::NULL_VALUE) {
                                $domNodeValue = $domainWithoutSlash . $styleImageValue;
                            } else {
                                $domNodeValue = $domainUrl . $styleImageValue;
                            }
                        }
                        $this->mainArray[$this->currentId][self::NODE_VALUE] = $domNodeValue;
                    } else {
                        $domNodeValue                                        = $this->getNodeValue($subChildren, $subChildren->children());
                        
                        // CHECK PRIMITIVE PATTERN WITH DOM NODE VALUE
                        if (count($primitiveRegexPatterns) > 0 && $regexGroup == self::PRIMITIVE_GROUP_ID) {
                            foreach ($primitiveRegexPatterns as $primitiveRegex) {
                                if (preg_match("/".$primitiveRegex->pattern."/", trim($domNodeValue), $match)){
                                    $primitiveExist = true;
                                }
                            }
                        }

                        $this->mainArray[$this->currentId][self::NODE_VALUE] = $domNodeValue;
                        
                        foreach ($regexPatterns as $socialRegex) {
                            if (preg_match($socialRegex->pattern, $domNodeValue, $match)) {
                                $referenceClass  = $socialRegex->ref_class;
                                $referenceColumn = $socialRegex->ref_column;
                                $referenceTable  = $socialRegex->ref_table;
                                $referenceId     = $socialRegex->ref_id;
                            }
                        }                       
                                              
                    }
                }
                if (isset($referenceClass)) {
                    $this->mainArray[$this->currentId]['class_data']     = $referenceClass;
                    $nodeField                                           = $referenceClass;
                }
                if (isset($referenceColumn)) {
                    $this->mainArray[$this->currentId]['ref_column'] = $referenceColumn;
                } else if(in_array($this->currentId, $referenceNodeArray)) {
                	foreach ($nodeReferenceInfo as $referenceData) {
                		if ($referenceData['node_id'] == $this->currentId) {
                			$this->mainArray[$this->currentId]['ref_column'] = $referenceData['reference_column'];
                		}
                	}
                } else {
                    $this->mainArray[$this->currentId]['ref_column'] = '';
                }
                if (isset($referenceTable)) {
                    $this->mainArray[$this->currentId]['ref_table'] = $referenceTable;
                } else if(in_array($this->currentId, $referenceNodeArray)) {
                	foreach ($nodeReferenceInfo as $referenceData) {
                		if ($referenceData['node_id'] == $this->currentId) {
                			$this->mainArray[$this->currentId]['ref_table'] = $referenceData['reference_table'];
                		}
                	}
                } else {
                    $this->mainArray[$this->currentId]['ref_table'] = '';
                }
                if (isset($referenceId)) {
                    $this->mainArray[$this->currentId]['ref_id'] = $referenceId;
                } else if(in_array($this->currentId, $referenceNodeArray)) {
                	foreach ($nodeReferenceInfo as $referenceData) {
                		if ($referenceData['node_id'] == $this->currentId) {
                			$this->mainArray[$this->currentId]['ref_id'] = $referenceData['reference_id'];
                		}
                	}
                } else {
                    $this->mainArray[$this->currentId]['ref_id'] = '';
                }

                if (in_array($this->currentId, $overrideNodeArray)) {
                    foreach ($manualClassOverride as $manualClass) {
                        if ($manualClass['node_id'] == $this->currentId && $manualClass['class_data'] == $nodeField) {
                            $overrideClass                                       = $manualClass['class_change'];
                            $this->mainArray[$this->currentId]['class_data']     = $overrideClass;
                        }
                    }
                }
                foreach ($careerPatterns as $referenceClassInfo) {
                    if($referenceClassInfo->ref_class == $nodeField) {
                        $classReferenceId = $this->getMatchReferenceId($referenceClassInfo, $domNodeValue);
                        $this->mainArray[$this->currentId]['ref_table'] = $referenceClassInfo->ref_table;
                        $this->mainArray[$this->currentId]['ref_column'] = $referenceClassInfo->ref_column;
                        if(!empty($classReferenceId)) {
                            $this->mainArray[$this->currentId]['ref_id'] = $classReferenceId;
                        }
                        if($regexGroup == self::MATCH_REFERENCE) {
                            $referenceClassExist = true;
                        }
                    }
                }
                $this->mainArray[$this->currentId][self::NODE_ID]        = $this->currentId;
                $this->mainArray[$this->currentId][self::PARENT_NODE_ID] = $parentNodeId;
                $this->mainArray[$this->currentId][self::PARENT_PATH]    = $parentNodePath;
                $this->mainArray[$this->currentId][self::NODE_INDEX]     = $initChild;
                $regexClassDetails                                       = Regex_class_override::where("node_id", $this->currentId)->where('entry_id', $identityId)->where('table_id', $identityTableId)->first();
                if (isset($regexClassDetails->override_id)) {
                    $htmlDomHistoryList = Regex_class_history::select('regex_class_history.*', 'regex_class_override.node_id')
                        ->join('regex_class_override', 'regex_class_override.override_id', 'regex_class_history.override_id')
                        ->where('regex_class_override.node_id', $this->currentId)
                        ->where('regex_class_override.entry_id', $identityId)
                        ->where('regex_class_override.table_id', $identityTableId)
                        ->get();
                    $history_count     = count($htmlDomHistoryList);
                    $class_change_data = 1;
                } else {
                    $history_count     = 0;
                    $class_change_data = 0;
                }
                $this->mainArray[$this->currentId]['history_count']     = $history_count;
                $this->mainArray[$this->currentId]['class_change_data'] = $class_change_data;
                $this->mainArray[$this->currentId]['identityTableId']   = $identityTableId;
                $website_url                                            = $this->getwebsiteUrlJson($website_url);
                if (!is_dir(storage_path() . self::HTMLDOM_DIR)) {
                    mkdir(storage_path() . self::HTMLDOM_DIR, 0777, true);
                }
                $jsonFilePath = storage_path() . self::HTMLDOM_DIR . $website_url . ".all" . ".json";
                if (file_exists($jsonFilePath)) {
                    $jsonNodeDetails = json_decode(file_get_contents($jsonFilePath), 1);
                    if (isset($jsonNodeDetails['scrapedDom'][$this->currentId])) {
                        $domStatus        = $jsonNodeDetails['scrapedDom'][$this->currentId]['status'];
                        $scrapedNodeValue = $jsonNodeDetails['scrapedDom'][$this->currentId]['value'];
                    } else {
                        $domStatus        = $this->domScrapeStatus->getScrapedStatus();
                        $scrapedNodeValue = $domNodeValue;
                    }
                    $this->mainArray[$this->currentId]['status'] = $domStatus;
                    if ($scrapedNodeValue != $domNodeValue) {
                        $this->mainArray[$this->currentId][self::DOM_CHANGES] = 'Yes';
                        $this->diffChanges = 1;
                    } else {
                        $this->mainArray[$this->currentId][self::DOM_CHANGES] = 'No';
                    }
                } else {
                    $this->mainArray[$this->currentId]['status']          = $this->domScrapeStatus->getScrapedStatus();
                    $this->mainArray[$this->currentId][self::DOM_CHANGES] = 'No';
                }
                if ((in_array($nodeField, $regexData) && trim($domNodeValue) != self::NULL_VALUE) || $socialUrlExist == true || (trim($nodeField) != self::NULL_VALUE && $nodeField == strtolower($regexLabel)) || $referenceClassExist == true || $primitiveExist == true) {
                    $this->mainArray[$this->currentId][self::INITIAL_CHECKED] = true;
                } else {
                    $this->mainArray[$this->currentId][self::INITIAL_CHECKED] = false;
                }

                $childCountData = 0;
                if ($subChildCount > 0) {
                    for ($childIndex = 0; $childIndex < $subChildCount; $childIndex++) {
                        $childTag = $subChildren->children($childIndex)->tag;
                        if (!in_array($childTag, $this->skipTag)) {
                            $childCountData = $childCountData + 1;
                        }
                    }
                }
                $this->mainArray[$this->currentId][self::NODE_CHILD] = $subChildCount;
                if($childCountData > 0) {
                    $this->mainArray[$this->currentId][self::HAS_CHILDREN] = true;
                }
                if ($subChildCount > 0) {
                    $this->parentNodePath                                  = $parentNodePath . $this->currentId . self::PATH_SLASH;
                    $this->parentId                                        = $this->currentId;
                    $this->currentId                                       = $this->currentId + 1;
                    $this->getLeafLevelValue($subChildren, $subChildCount, $this->parentId, $this->parentNodePath, $regexGroup, $domainUrl, $domainWithoutSlash, $regexLabel, $identityTableId, $identityTableName, $identityId, $website_url, $overrideNodeArray, $referenceNodeArray);
                } else {
                    $this->currentId = $this->currentId + 1;
                }
            }
        }
    }

    public function getNodeValue($childrenObject, $allChild)
    {
        foreach ($allChild as $childData) {
            $childData->outertext = self::NULL_VALUE;
        }
        return $childrenObject->innertext;
    }

    public function addWebsiteUrl($websiteUri, $requestUrl, $identityTableName)
    {
        if ($websiteUri == $requestUrl) {
            $requestUrl = preg_replace('#http://|https://|www.#', '', $requestUrl);
        }
        $websiteList = DB::table($identityTableName)
            ->where('identity_website', $websiteUri)
            ->orWhere('identity_website', $requestUrl)
            ->get()
            ->first();
        if (!$websiteList) {
            $identityId = DB::table($identityTableName)->insertGetId(
                ['identity_website' => $websiteUri]
            );
        }
        else {
            $identityId = $websiteList->identity_id;
        }
        return $identityId;
    }

    public function setInitialScrapeStatus($identityTableId, $identityId, $diffChanges)
    {
        $scrapeStatusInfo = Scrape_status_history::where("identity_id", $identityId)->where("identity_table_id", $identityTableId)->get()->first();
        if ($scrapeStatusInfo) {
            $scrapeStatusHistory              = Scrape_status_history::findOrfail($scrapeStatusInfo->history_id);
            $scrapeStatusHistory->scrape_date = date('Ymd');
            $scrapeStatusHistory->owner       = $this->userId;
            $scrapeStatusHistory->diff_changes = $diffChanges;
            $scrapeStatusHistory->save();
        } else {
            $scrapeStatusHistory                    = new Scrape_status_history();
            $scrapeStatusHistory->identity_table_id = $identityTableId;
            $scrapeStatusHistory->identity_id       = $identityId;
            $scrapeStatusHistory->scrape_date       = date('Ymd');
            $scrapeStatusHistory->setup_date        = date('Ymd');
            $scrapeStatusHistory->cron_date         = '';
            $scrapeStatusHistory->owner             = $this->userId;
            $scrapeStatusHistory->diff_changes      = $diffChanges;
            $scrapeStatusHistory->save();
        }
        return;
    }

    public function cleanUserReferenceData() {
        RegexReferenceValue::where('user_id', $this->userId)->delete();
        return;
    }

    public function scrapeHtmlDom(Request $request)
    {
        $regexGroup          = $request->regex_group;
        $regexLabel          = $request->regex_label;
        $regexPatterns       = $this->getRegexPatterns(1);
        $regexData           = $this->getRegexFields($regexGroup);
        
        $socialRegexPatterns = [];
        $primitiveRegexPatterns = [];
        if ($regexGroup != '' && $regexGroup != self::PRIMITIVE_GROUP_ID) {            
            $socialRegexPatterns = $this->getSocialRegexPatterns($regexGroup);
        }else if ($regexGroup != '' && $regexGroup == self::PRIMITIVE_GROUP_ID) {            
            $primitiveRegexPatterns = $this->getPrimitiveRegexPatterns();
        }
        $websiteUri        = $request->website_uri;
        $identityTableId   = $request->identity_table;
        $identityTableData = Identity_table_type::select('table_code')
            ->where('type_id', $identityTableId)->get()->first();
        $identityTableName   = $identityTableData->table_code;
        $identityId          = $request->identity_id;
        $manualClassOverride = $this->getManualOverrideClass($identityTableId, $identityId);
        $overrideNodeArray   = [];
        foreach ($manualClassOverride as $manualClass) {
            array_push($overrideNodeArray, $manualClass['node_id']);
        }
        $nodeReferenceInfo = $this->getNodeReferenceInfo($identityTableId, $identityId);
        $careerPatterns = $this->getRegexPatterns(self::MATCH_REFERENCE);
        $referenceNodeArray = [];
        foreach ($nodeReferenceInfo as $referenceNodeData) {
            array_push($referenceNodeArray, $referenceNodeData['node_id']);
        }
        if (strpos($websiteUri, 'http://') === false && strpos($websiteUri, 'https://') === false) {
            $websiteUri = 'http://' . $request->website_uri;
        } else {
            $websiteUri = $request->website_uri;
        }
        $website_url = preg_replace('#http://|https://|www.#', '', $websiteUri);
        $splitUrl           = explode("//", $websiteUri);
        $splitUrlData       = explode(self::PATH_SLASH, $splitUrl[1]);
        $domainUrl          = $splitUrl[0] . "//" . $splitUrlData[0] . self::PATH_SLASH;
        $domainWithoutSlash = $splitUrl[0] . "//" . $splitUrlData[0];
        $website_url        = $this->getwebsiteUrlJson($website_url);
        if (!is_dir(storage_path() . self::HTMLDOM_DIR)) {
            mkdir(storage_path() . self::HTMLDOM_DIR, 0777, true);
        }
        $jsonFilePath = storage_path() . self::HTMLDOM_DIR . $website_url . ".all.json";
        try {
            $websiteHtml = HtmlDomParser::file_get_html($websiteUri);
            if ($websiteHtml) {
                $this->cleanUserReferenceData();
                $mainElement    = $websiteHtml->find('body');
                $insertedIdentityId = $this->addWebsiteUrl($websiteUri, $request->website_uri, $identityTableName);
                if(empty($identityId) && !empty($insertedIdentityId)) {
                    $identityId = $insertedIdentityId;
                }
                $totalMainChild = count($mainElement[0]->children());
                for ($initData = 0; $initData < $totalMainChild; $initData++) {
                    unset($referenceClass);
                    unset($referenceColumn);
                    unset($referenceId);
                    unset($referenceTable);
                    $socialUrlExist = false;
                    $referenceClassExist = false;
                    $primitiveExist = false;
                    $childrenObject = $mainElement[0]->children($initData);
                    $childCount     = count($childrenObject->children());
                    if ($childCount > 0 && !in_array($childrenObject->tag, $this->skipTag)) {
                        $this->mainArray[$this->currentId][self::NODE_TAG] = $childrenObject->tag;
                        if (isset($childrenObject->id)) {
                            $this->mainArray[$this->currentId][self::NODE_FIELD] = $childrenObject->id;
                            $nodeField                                           = $childrenObject->id;
                        } else if (isset($childrenObject->class)) {
                            $this->mainArray[$this->currentId][self::NODE_FIELD] = $childrenObject->class;
                            $nodeField                                           = $childrenObject->class;
                        } else {
                            $this->mainArray[$this->currentId][self::NODE_FIELD] = self::NULL_VALUE;
                            $nodeField                                           = self::NULL_VALUE;
                        }
                        if ($childrenObject->tag == self::ANCHOR_TAG) {
                            $anchorNodeValue = $this->getNodeValue($childrenObject, $childrenObject->children());
                            if (isset($childrenObject->href)) {
                                if (preg_match(self::URL_REGEX, $childrenObject->href)) {
                                    $domNodeValue = $childrenObject->href;
                                } else {
                                    $splitNodeValue = explode(self::PATH_SLASH, $childrenObject->href);
                                    if ($splitNodeValue[0] == self::NULL_VALUE) {
                                        $domNodeValue = $domainWithoutSlash . $childrenObject->href;
                                    } else {
                                        $domNodeValue = $domainUrl . $childrenObject->href;
                                    }
                                }
                                $this->mainArray[$this->currentId][self::NODE_VALUE] = $domNodeValue;
                            } else if(!empty($anchorNodeValue)) {
                                $this->mainArray[$this->currentId][self::NODE_VALUE] = $anchorNodeValue;
                                $domNodeValue = $anchorNodeValue;
                            }
                            else {
                                $this->mainArray[$this->currentId][self::NODE_VALUE] = self::NULL_VALUE;
                                $domNodeValue                                        = self::NULL_VALUE;
                            }
                            $socialUrl = self::ANCHOR_HREF . $childrenObject->href . self::COMMA;
                            if (count($socialRegexPatterns) > 0 && $regexGroup == 1) {
                                foreach ($socialRegexPatterns as $socialRegex) {
                                    if (preg_match($socialRegex->pattern, $socialUrl, $match)) {
                                        $socialUrlExist  = true;
                                        $referenceClass  = $socialRegex->ref_class;
                                        $referenceColumn = $socialRegex->ref_column;
                                        $referenceTable  = $socialRegex->ref_table;
                                        $referenceId     = $socialRegex->ref_id;
                                    }
                                }
                            } else if ($regexPatterns) {
                                foreach ($regexPatterns as $socialRegex) {
                                    if (preg_match($socialRegex->pattern, $socialUrl, $match)) {
                                        $referenceClass  = $socialRegex->ref_class;
                                        $referenceColumn = $socialRegex->ref_column;
                                        $referenceTable  = $socialRegex->ref_table;
                                        $referenceId     = $socialRegex->ref_id;
                                    }
                                }
                            }
                        } else if ($childrenObject->tag == 'img') {
                            if (isset($childrenObject->src)) {
                                if (preg_match(self::URL_REGEX, $childrenObject->src)) {
                                    $domNodeValue = $childrenObject->src;
                                } else {
                                    $splitNodeValue = explode(self::PATH_SLASH, $childrenObject->src);
                                    if ($splitNodeValue[0] == self::NULL_VALUE) {
                                        $domNodeValue = $domainWithoutSlash . $childrenObject->src;
                                    } else {
                                        $domNodeValue = $domainUrl . $childrenObject->src;
                                    }
                                }
                                $this->mainArray[$this->currentId][self::NODE_VALUE] = $domNodeValue;
                            } else {
                                $this->mainArray[$this->currentId][self::NODE_VALUE] = self::NULL_VALUE;
                                $domNodeValue                                        = self::NULL_VALUE;
                            }
                        } else {
                            $nodeStyle = $childrenObject->style;
                            preg_match(self::IMAGE_PATTERN, $nodeStyle, $matchData);
                            if (isset($matchData) && count($matchData) > 0) {
                                $styleImageValue = $matchData[1];
                                if (preg_match(self::URL_REGEX, $styleImageValue)) {
                                    $domNodeValue = $styleImageValue;
                                } else {
                                    $splitNodeValue = explode(self::PATH_SLASH, $styleImageValue);
                                    if ($splitNodeValue[0] == self::NULL_VALUE) {
                                        $domNodeValue = $domainWithoutSlash . $styleImageValue;
                                    } else {
                                        $domNodeValue = $domainUrl . $styleImageValue;
                                    }
                                }
                                $this->mainArray[$this->currentId][self::NODE_VALUE] = $domNodeValue;
                            } else {
                                $domNodeValue                                        = $this->getNodeValue($childrenObject, $childrenObject->children());

                                // CHECK PRIMITIVE PATTERN WITH DOM NODE VALUE
                                if (count($primitiveRegexPatterns) > 0 && $regexGroup == self::PRIMITIVE_GROUP_ID) {
                                    foreach ($primitiveRegexPatterns as $primitiveRegex) {
                                        if (preg_match("/".$primitiveRegex->pattern."/", trim($domNodeValue), $match)){
                                            $primitiveExist = true;
                                        }
                                    }
                                } 

                                $this->mainArray[$this->currentId][self::NODE_VALUE] = $domNodeValue;

                                foreach ($regexPatterns as $socialRegex) {
                                    if (preg_match($socialRegex->pattern, $domNodeValue, $match)) {
                                        $referenceClass  = $socialRegex->ref_class;
                                        $referenceColumn = $socialRegex->ref_column;
                                        $referenceTable  = $socialRegex->ref_table;
                                        $referenceId     = $socialRegex->ref_id;
                                    }
                                }   
                            }
                        }
                        if (isset($referenceClass)) {
                            $this->mainArray[$this->currentId]['class_data']     = $referenceClass;
                            $nodeField                                           = $referenceClass;
                        }
                        if (isset($referenceColumn)) {
                            $this->mainArray[$this->currentId]['ref_column'] = $referenceColumn;
                        } else if(in_array($this->currentId, $referenceNodeArray)) {
                        	foreach ($nodeReferenceInfo as $referenceData) {
                        		if ($referenceData['node_id'] == $this->currentId) {
                        			$this->mainArray[$this->currentId]['ref_column'] = $referenceData['reference_column'];
                        		}
                        	}
                        } else {
                            $this->mainArray[$this->currentId]['ref_column'] = '';
                        }
                        if (isset($referenceTable)) {
                            $this->mainArray[$this->currentId]['ref_table'] = $referenceTable;
                        } else if(in_array($this->currentId, $referenceNodeArray)) {
                        	foreach ($nodeReferenceInfo as $referenceData) {
                        		if ($referenceData['node_id'] == $this->currentId) {
                        			$this->mainArray[$this->currentId]['ref_table'] = $referenceData['reference_table'];
                        		}
                        	}
                        } else {
                            $this->mainArray[$this->currentId]['ref_table'] = '';
                        }
                        if (isset($referenceId)) {
                            $this->mainArray[$this->currentId]['ref_id'] = $referenceId;
                        } else if(in_array($this->currentId, $referenceNodeArray)) {
                        	foreach ($nodeReferenceInfo as $referenceData) {
                        		if ($referenceData['node_id'] == $this->currentId) {
                        			$this->mainArray[$this->currentId]['ref_id'] = $referenceData['reference_id'];
                        		}
                        	}
                        } else {
                            $this->mainArray[$this->currentId]['ref_id'] = '';
                        }

                        if (in_array($this->currentId, $overrideNodeArray)) {
                            foreach ($manualClassOverride as $manualClass) {
                                if ($manualClass['node_id'] == $this->currentId && $manualClass['class_data'] == $nodeField) {
                                    $overrideClass                                       = $manualClass['class_change'];
                                    $nodeField = $overrideClass;
                                }
                            }
                        }
                        foreach ($careerPatterns as $referenceClassInfo) {
                            if($referenceClassInfo->ref_class == $nodeField) {
                                $classReferenceId = $this->getMatchReferenceId($referenceClassInfo, $domNodeValue);
                                $this->mainArray[$this->currentId]['ref_table'] = $referenceClassInfo->ref_table;
                                $this->mainArray[$this->currentId]['ref_column'] = $referenceClassInfo->ref_column;
                                if(!empty($classReferenceId)) {
                                    $this->mainArray[$this->currentId]['ref_id'] = $classReferenceId;
                                }
                                if($regexGroup == self::MATCH_REFERENCE) {
                                    $referenceClassExist = true;
                                }
                            }
                        }
                        $this->mainArray[$this->currentId][self::NODE_ID]        = $this->currentId;
                        $this->mainArray[$this->currentId][self::PARENT_NODE_ID] = $this->parentId;
                        $this->mainArray[$this->currentId][self::PARENT_PATH]    = $this->parentNodePath;
                        $this->mainArray[$this->currentId][self::HAS_CHILDREN]   = true;
                        $this->mainArray[$this->currentId][self::NODE_CHILD]     = $childCount;
                        $this->mainArray[$this->currentId][self::NODE_INDEX]     = $initData;
                        $this->mainArray[$this->currentId]['identityTableId']    = $identityTableId;
                        $this->mainArray[$this->currentId]['class_data']         = $nodeField;
                        if (file_exists($jsonFilePath)) {
                            $jsonNodeDetails = json_decode(file_get_contents($jsonFilePath), 1);
                            if (isset($jsonNodeDetails['scrapedDom'][$this->currentId])) {
                                $domStatus        = $jsonNodeDetails['scrapedDom'][$this->currentId]['status'];
                                $scrapedNodeValue = $jsonNodeDetails['scrapedDom'][$this->currentId]['value'];
                            } else {
                                $domStatus        = $this->domScrapeStatus->getScrapedStatus();
                                $scrapedNodeValue = $domNodeValue;
                            }
                            $this->mainArray[$this->currentId]['status'] = $domStatus;
                            if ($scrapedNodeValue != $domNodeValue) {
                                $this->mainArray[$this->currentId][self::DOM_CHANGES] = 'Yes';
                                $this->diffChanges = 1;
                            } else {
                                $this->mainArray[$this->currentId][self::DOM_CHANGES] = 'No';
                            }
                        } else {
                            $this->mainArray[$this->currentId]['status']          = $this->domScrapeStatus->getScrapedStatus();
                            $this->mainArray[$this->currentId][self::DOM_CHANGES] = 'No';
                        }
                        $regexClassDetails = Regex_class_override::where("node_id", $this->currentId)->where('entry_id', $identityId)->where('table_id', $identityTableId)->first();
                        if (isset($regexClassDetails->override_id)) {
                            $htmlDomHistoryList = Regex_class_history::select('regex_class_history.*', 'regex_class_override.node_id')
                                ->join('regex_class_override', 'regex_class_override.override_id', 'regex_class_history.override_id')
                                ->where('regex_class_override.node_id', $this->currentId)
                                ->where('regex_class_override.entry_id', $identityId)
                                ->where('regex_class_override.table_id', $identityTableId)
                                ->get();
                            $history_count     = count($htmlDomHistoryList);
                            $class_change_data = 1;
                        } else {
                            $history_count     = 0;
                            $class_change_data = 0;
                        }


                        $this->mainArray[$this->currentId]['history_count']     = $history_count;
                        $this->mainArray[$this->currentId]['class_change_data'] = $class_change_data;
                        if ((in_array($nodeField, $regexData) && trim($domNodeValue) != self::NULL_VALUE) || $socialUrlExist == true || (trim($nodeField) != self::NULL_VALUE && $nodeField == strtolower($regexLabel)) || $referenceClassExist == true || $primitiveExist == true) {
                            $this->mainArray[$this->currentId][self::INITIAL_CHECKED] = true;
                        } else {
                            $this->mainArray[$this->currentId][self::INITIAL_CHECKED] = false;
                        }
                        $this->parentNodePath = $this->currentId . self::PATH_SLASH;
                        $this->parentId       = $this->currentId;
                        $this->currentId      = $this->currentId + 1;
                        $this->getLeafLevelValue($childrenObject, $childCount, $this->parentId, $this->parentNodePath, $regexGroup, $domainUrl, $domainWithoutSlash, $regexLabel, $identityTableId, $identityTableName, $identityId, $website_url, $overrideNodeArray, $referenceNodeArray);
                    }
                    $this->parentId       = null;
                    $this->parentNodePath = self::NULL_VALUE;
                }
                $this->setInitialScrapeStatus($identityTableId, $identityId, $this->diffChanges);

                $websiteDataDetails = [];
                $websiteDataDetails['regex_group'] = $regexGroup;
                foreach ($this->mainArray as $domNode) {
                    $nodeId                      = $domNode['nodeId'];
                    $websiteDataDetails['scrapedDom'][$nodeId] = array(
                        "nodeId"       => $domNode['nodeId'],
                        "tag"          => $domNode['tag'],
                        "field"        => $domNode['field'],
                        "class_data"   => $domNode['class_data'],
                        "value"        => $domNode['value'],
                        "parentNodeId" => $domNode['parentNodeId'],
                        "child"        => $domNode['child'],
                        "class_change_data" => $domNode['class_change_data'],
                        "dom_changes"  => $domNode['dom_changes'],
                        "history_count" => $domNode['history_count'],
                        "identityTableId" => $domNode['identityTableId'],
                        "nodeIndex"    => $domNode['nodeIndex'],
                        "path"         => $domNode['path'],
                        "ref_column"   => $domNode['ref_column'],
                        "ref_id"       => $domNode['ref_id'],
                        "ref_table"    => $domNode['ref_table'],
                        "selected"     => $domNode['selected'],
                        "status"    => $domNode['status']
                    );
                    if(isset($domNode['hasChildren'])) {
                        $websiteDataDetails['scrapedDom'][$nodeId]['hasChildren'] = $domNode['hasChildren'];
                    }
                }
                if (!file_exists($jsonFilePath)) {
                    $websiteJsonDetail = json_encode($websiteDataDetails);
                    file_put_contents($jsonFilePath, $websiteJsonDetail);
                } else {
                    $jsonNodeDetails = json_decode(file_get_contents($jsonFilePath), 1);
                    foreach ($this->mainArray as $domNode) {
                        $nodeId          = $domNode['nodeId'];
                        if (isset($jsonNodeDetails['scrapedDom'][$nodeId])) {
                            if (($jsonNodeDetails['scrapedDom'][$nodeId]['value'] != $domNode['value']) || ($jsonNodeDetails['scrapedDom'][$nodeId]['field'] != $domNode['field'])) {
                                $jsonNodeDetails['scrapedDom'][$nodeId]['tag'] = $domNode['tag'];
                                $jsonNodeDetails['scrapedDom'][$nodeId]['field'] = $domNode['field'];
                                $jsonNodeDetails['scrapedDom'][$nodeId]['class_data'] = $domNode['class_data'];
                                $jsonNodeDetails['scrapedDom'][$nodeId]['value'] = $domNode['value'];
                                $jsonNodeDetails['scrapedDom'][$nodeId]['parentNodeId'] = $domNode['parentNodeId'];
                                $jsonNodeDetails['scrapedDom'][$nodeId]['status'] = $this->domScrapeStatus->getUpdatedStatus();
                            }
                        } else {
                            $jsonNodeDetails['scrapedDom'][$nodeId] = array(
                                    "nodeId"       => $domNode['nodeId'],
                                    "tag"          => $domNode['tag'],
                                    "field"        => $domNode['field'],
                                    "class_data"   => $domNode['class_data'],
                                    "value"        => $domNode['value'],
                                    "parentNodeId" => $domNode['parentNodeId'],
                                    "child"        => $domNode['child'],
                                    "class_change_data" => $domNode['class_change_data'],
                                    "dom_changes"  => $domNode['dom_changes'],
                                    "history_count" => $domNode['history_count'],
                                    "identityTableId" => $domNode['identityTableId'],
                                    "nodeIndex"    => $domNode['nodeIndex'],
                                    "path"         => $domNode['path'],
                                    "ref_column"   => $domNode['ref_column'],
                                    "ref_id"       => $domNode['ref_id'],
                                    "ref_table"    => $domNode['ref_table'],
                                    "selected"     => $domNode['selected'],
                                    "status"    => $domNode['status']
                                );
                        }
                    }
                    $websiteJsonDetail = json_encode($jsonNodeDetails);
                    file_put_contents($jsonFilePath, $websiteJsonDetail);
                }
                return json_encode($this->mainArray);
            }
        } catch (Exception $errorData) {
            Debug::log($errorData->getMessage());
        }
    }

    public function saveDomNodeValues(Request $request)
    {
        $domainUrl      = $request->domain_url;
        $regexGroup     = $request->regex_group;
        $websiteUrl     = preg_replace('#http://|https://|www.#', '', $domainUrl);
        $websiteUrlData = $this->getwebsiteUrlJson($websiteUrl);
        if (!is_dir(storage_path() . self::HTMLDOM_DIR)) {
            mkdir(storage_path() . self::HTMLDOM_DIR, 0777, true);
        }
        $selectedNode    = json_decode($request->selected_node);
        $checkedNodeData = array();
        foreach ($selectedNode as $nodeData) {
        	$checkedNodeGroups = CheckedNodeGroups::where("identity_table_id", $nodeData->identity_table_id)
                ->where("identity_id", $nodeData->identity_id)
                ->where("node_id", $nodeData->nodeId)
                ->get()->first();
            if (!$checkedNodeGroups) {
                $checkedNodeData[] = array(
                    'identity_table_id' => $nodeData->identity_table_id,
                    'identity_id'       => $nodeData->identity_id,
                    'group_id'          => $nodeData->rootParent,
                    'node_id'           => $nodeData->nodeId,
                    'parent_node_id' 	=> $nodeData->parentNodeId,
                    'node_tag'       	=> $nodeData->tag,
                    'node_class'        => $nodeData->field,
                    'node_value'        => $nodeData->value,
                );
            }
            else {
                $updateCheckedNode             = CheckedNodeGroups::findOrfail($checkedNodeGroups->checked_id);
                $updateCheckedNode->group_id   = $nodeData->rootParent;
                $updateCheckedNode->parent_node_id = $nodeData->parentNodeId;
                $updateCheckedNode->node_tag   = $nodeData->tag;
                $updateCheckedNode->node_class = $nodeData->field;
                $updateCheckedNode->node_value = $nodeData->value;
                $updateCheckedNode->save();
            }
        }
        CheckedNodeGroups::insert($checkedNodeData);
        $jsonFilePath = storage_path() . self::HTMLDOM_DIR . $websiteUrlData . ".checked.json";
        file_put_contents($jsonFilePath, $request->selected_node);
        if(!empty($request->all_dom_node)) {
            $allDomNode = json_decode($request->all_dom_node);
            $urlAllDomDetails = [];
            $urlAllDomDetails['regex_group'] = $regexGroup;
            $urlAllDomDetails['scrapedDom'] = $allDomNode;
            $allNodeJsonFilePath = storage_path() . self::HTMLDOM_DIR . $websiteUrlData . ".all.json";
            $allNodeJsonData = json_encode($urlAllDomDetails);
            file_put_contents($allNodeJsonFilePath, $allNodeJsonData);
        }
        return;
    }

    public function getReferenceNodeDetails(Request $request)
    {
        $nodeId             = $request->node_id;
        $identityTableId    = $request->identity_table;
        $identityId         = $request->identity_id;
        $referenceId        = $request->reference_id;
        $referenceTable     = $request->reference_table;
        $referenceColumn    = $request->reference_column;
        $regexOverrideClass = Regex_reference_node::select(
            'regex_reference_node.*')
            ->where('regex_reference_node.node_id', $nodeId)
            ->where('regex_reference_node.identity_table_id', $identityTableId)
            ->where('regex_reference_node.identity_id', $identityId)
            ->get()->first();
        if(!empty($referenceId) && !empty($referenceTable) && !empty($referenceColumn)) {
            $referenceInfo['reference_table']  = $referenceTable;
            $referenceInfo['reference_column'] = $referenceColumn;
            $referenceInfo['reference_id']     = $referenceId;
            return json_encode($referenceInfo);
        }
        else if (count($regexOverrideClass) > 0) {
            return json_encode($regexOverrideClass);
        } else {
            $referenceInfo['reference_table']  = '';
            $referenceInfo['reference_column'] = '';
            $referenceInfo['reference_id']     = '';
            return json_encode($referenceInfo);
        }
    }

    public function assignReferenceInfo(Request $request)
    {
        $regexReferenceNode = Regex_reference_node::select(
            'regex_reference_node.*')
            ->where('regex_reference_node.node_id', $request->node_id)
            ->where('regex_reference_node.identity_table_id', $request->identity_table_id)
            ->where('regex_reference_node.identity_id', $request->identity_id)
            ->get()
            ->first();
        if ($regexReferenceNode) {
            $referenceNode                    = Regex_reference_node::findOrfail($regexReferenceNode->id);
            $referenceNode->node_id           = $request->node_id;
            $referenceNode->identity_table_id = $request->identity_table_id;
            $referenceNode->identity_id       = $request->identity_id;
            $referenceNode->reference_table   = $request->reference_table;
            $referenceNode->reference_column  = $request->reference_column;
            $referenceNode->save();
        } else {
            $referenceNode                    = new Regex_reference_node();
            $referenceNode->node_id           = $request->node_id;
            $referenceNode->identity_table_id = $request->identity_table_id;
            $referenceNode->identity_id       = $request->identity_id;
            $referenceNode->reference_table   = $request->reference_table;
            $referenceNode->reference_column  = $request->reference_column;
            $referenceNode->save();
        }
        $nodeReferenceInfo = Regex_reference_node::select(
            'regex_reference_node.*')
            ->where('regex_reference_node.node_id', $request->node_id)
            ->where('regex_reference_node.identity_table_id', $request->identity_table_id)
            ->where('regex_reference_node.identity_id', $request->identity_id)
            ->get()
            ->first();
        return json_encode($nodeReferenceInfo);
    }

    public function getScrapeUrlStatus(Request $requestData) {
        $scrapeUrlStatus = Scrape_status_history::select('scrape_status_history.*',
        	'identity_table_type.table_code as identity_table',
        	'portal_password.username as owner_name')
        	->join('identity_table_type', 'identity_table_type.type_id', 'scrape_status_history.identity_table_id')
            ->join('portal_password','portal_password.user_id','scrape_status_history.owner')
            ->where("scrape_status_history.owner", $this->userId)
            ->get();
        foreach ($scrapeUrlStatus as $keyData => $resultData) {
            $identityTable = $resultData->identity_table;
            $websiteUrl    = DB::table($identityTable)
                ->select('identity_website')
                ->where('identity_id', $resultData->identity_id)
                ->get()->first();
            $identityWebsite = $websiteUrl->identity_website;
            $scrapeUrlStatus[$keyData]->website_url = $identityWebsite;
            $scrapeUrlStatus[$keyData]->save_date = $resultData->scrape_date;
        }
        return json_encode($scrapeUrlStatus);
    }

    public function getUserScrapeLists() {
        $userScrapeLists = Scrape_status_history::where("scrape_status_history.owner", $this->userId)->get();
        return count($userScrapeLists);
    }

    public function getTargetTables() {
        $tableLists  = RegexTableAccess::select('table_name')
            ->get();
        return json_encode($tableLists);
    }

    public function getTargetFieldsDetails(Request $requestData) {
        $identityId      = $requestData->identity_id;
        $identityTableId = $requestData->identity_table_id;
        $domClass = $requestData->dom_class;
        $checkedDomNode = json_decode($requestData->checked_node);
        $mapTargetFields = RegexMapTargetFields::select('regex_map_target_fields.*', 
            'identity_asset.identity_code as target_table_code')
            ->join('asset', 'asset.asset_id','regex_map_target_fields.target_table_ref_id')
            ->join('identity_asset', 'identity_asset.identity_id','asset.identity_id')
            ->where('regex_map_target_fields.identity_table_id', $identityTableId)
            ->where('regex_map_target_fields.identity_id', $identityId)
            ->get();
        $nodeTargetFieldArray = [];
        foreach ($mapTargetFields as $keyData => $mappingFields) {
            array_push($nodeTargetFieldArray, $mappingFields->dom_class);
        }
        $nodeFieldArray = [];
        foreach ($checkedDomNode as $nodeData) {
            if (!in_array($nodeData->field, $nodeTargetFieldArray) && !in_array($nodeData->field, $nodeFieldArray)) {
                array_push($nodeFieldArray, $nodeData->field);
            }
        }
        $mapTargetArray = $mapTargetFields->toArray();
        foreach ($nodeFieldArray as $nodeFieldData) {
            $targetInfo['identity_table_id']  = $identityTableId;
            $targetInfo['identity_id']  = $identityId;
            $targetInfo['dom_class']  = $nodeFieldData;
            $targetInfo['mapping_table']  = '';
            $targetInfo['mapping_foreign_column'] = '';
            $targetInfo['mapping_value_column'] = '';
            $targetInfo['target_table']  = '';
            $targetInfo['target_table_ref_id']  = '';
            $targetInfo['target_table_code']  = '';
            array_push($mapTargetArray, $targetInfo);
        }
        
        $dom_class_match_array = [];
        $dom_class_array       = [];
        foreach ($mapTargetArray as $key => $value) {
            if ($value['dom_class'] == $domClass) {
                $dom_class_match_array[0] = $value;
            } else {
                $dom_class_array[] = $value;
            }
        }
        $mapTargetDetails = array_merge($dom_class_match_array, $dom_class_array);
        return $mapTargetDetails = json_encode($mapTargetDetails);
    }

    public function getTargetTableColumns(Request $requestData) {
        $tableName      = $requestData->target_table;
        $allColumns     = Schema::getColumnListing($tableName);
        $allColumnsData = [];
        foreach ($allColumns as $columnValue) {
            $allColumnsData[]['column_name'] = $columnValue;
        }
        return json_encode($allColumnsData);
    }

    public function saveTargetTableInfo(Request $requestData) {
        $identityId      = $requestData->identity_id;
        $identityTableId = $requestData->identity_table_id;
        $domClass = $requestData->dom_class;
        $mapTargetFields = RegexMapTargetFields::select('regex_map_target_fields.*')
            ->where('regex_map_target_fields.identity_table_id', $identityTableId)
            ->where('regex_map_target_fields.identity_id', $identityId)
            ->where('regex_map_target_fields.dom_class', $domClass)
            ->get()->first();
        if ($mapTargetFields) {
            $targetMapInfo = RegexMapTargetFields::findOrfail($mapTargetFields->map_id);
            $targetMapInfo->mapping_table = $requestData->mapping_table;
            $targetMapInfo->mapping_foreign_column = $requestData->mapping_foreign_column;
            $targetMapInfo->mapping_value_column = $requestData->mapping_value_column;
            $targetMapInfo->target_table = $requestData->target_table;
            $targetMapInfo->reference_column = $requestData->reference_column;
            $targetMapInfo->target_table_ref_id = $requestData->target_table_ref_id;
            $targetMapInfo->extra_foreign_column = $requestData->extra_foreign_column;
            $targetMapInfo->save();
        } else {
            $targetMapInfo = new RegexMapTargetFields();
            $targetMapInfo->identity_table_id = $requestData->identity_table_id;
            $targetMapInfo->identity_id = $requestData->identity_id;
            $targetMapInfo->dom_class = $requestData->dom_class;
            $targetMapInfo->mapping_table = $requestData->mapping_table;
            $targetMapInfo->mapping_foreign_column = $requestData->mapping_foreign_column;
            $targetMapInfo->mapping_value_column = $requestData->mapping_value_column;
            $targetMapInfo->target_table = $requestData->target_table;
            $targetMapInfo->reference_column = $requestData->reference_column;
            $targetMapInfo->target_table_ref_id = $requestData->target_table_ref_id;
            $targetMapInfo->extra_foreign_column = $requestData->extra_foreign_column;
            $targetMapInfo->save();
        }
        return;
    }

    public function getReferenceData() {
        $referenceData = RegexReferenceValue::select('regex_reference_value.*')
            ->where('user_id', $this->userId)
            ->get();
        return json_encode($referenceData);
    }

    public function updateReferenceData(Request $requestData) {
        $referenceData = RegexReferenceValue::findOrfail($requestData->id);
        $referenceData->reference_value = $requestData->reference_value;
        $referenceData->save();
    }

    public function deleteReferenceData(Request $requestData) {
        $referenceData = RegexReferenceValue::findOrfail($requestData->id);
        $referenceData->delete();
    }

    public function saveLookupData(Request $requestData) {
        $referenceData = json_decode($requestData->reference_data);

        foreach ($referenceData as $valueData) {
            $referenceClass = $valueData->reference_key;
            $crosswalkSchema = self::CROSSWALK_PREFIX.$referenceClass;

            $referenceMapData = Regex_map_category::select('regex_map_category.ref_table',
                'regex_map_category.ref_column')
                ->where('regex_map_category.ref_class', $referenceClass)
                ->get()
                ->first();
            $referenceClassData = DB::table($referenceMapData->ref_table)
                ->where($referenceMapData->ref_column,'like',$valueData->reference_value)
                ->get()->first();
            if (!$referenceClassData) {
                $recordId = DB::table($referenceMapData->ref_table)->insertGetId(
                    [$referenceMapData->ref_column => $valueData->reference_value]
                );
                DB::table($crosswalkSchema)
                        ->where($referenceMapData->ref_column,$valueData->original_value)
                        ->update(['lookup_id' => $recordId]);                
            }else{
                DB::table($referenceMapData->ref_table)->where($referenceMapData->ref_column, $valueData->reference_value)->update([$referenceMapData->ref_column => $valueData->reference_value]);
            }
        }
    }

    public function getAllAsset() {
        $allAsset = Asset::select(
            'asset.asset_id as target_table_ref_id',
            'identity_asset.identity_code as target_table_code')
            ->join('identity_asset', 'identity_asset.identity_id', 'asset.identity_id')
            ->where('asset_id', '!=', 0)
            ->get();
        return json_encode($allAsset);
    }

    public function getAllSocials() {
        $allSocials = Social::select(
            'social.social_id as target_table_ref_id',
            'identity_social.identity_code as target_table_code')
            ->join('identity_social', 'identity_social.identity_id', 'social.identity_id')
            ->where('social_id', '!=', 0)
            ->get();
        return json_encode($allSocials);
    }

    public function getTableForeignKey($tableName) {
        $foreignKeyData = array();
        $foreignKeyQuery = "SELECT COLUMN_NAME,REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE REFERENCED_TABLE_SCHEMA = 'dev_v400' AND TABLE_NAME = '".$tableName."'";

        $resultsData = DB::select(DB::raw($foreignKeyQuery));
        if(count($resultsData) > 0){
            foreach ($resultsData as $value) {
                $foreignKeyData[$value->REFERENCED_TABLE_NAME] = $value->COLUMN_NAME;
            }
        }
        return $foreignKeyData;
    }

    public function getTablePrimaryKey($tableName) {
        $primaryKeyData = "";
        $primaryKeyQuery = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.key_column_usage WHERE table_schema = 'dev_v400' AND CONSTRAINT_NAME = 'PRIMARY' AND TABLE_NAME = '".$tableName."'";

        $resultsData = DB::select(DB::raw($primaryKeyQuery));
        if(count($resultsData) > 0){
            $primaryKey = $resultsData[0]->COLUMN_NAME;   
        }
        return $primaryKey;
    }

    public function getTableColumns($tableName) {
        $allColumns = DB::select('show columns from ' . $tableName);
        return $allColumns;
    }

    public function multi_array_search($array, $search)
    {
        // Create the result array
        $result = array();

        // Iterate over each array element
        foreach ($array as $key => $value)
        {
          if(isset($search['table']) && isset($search['column']) && $search['class']){  
              if($value['table'] == $search['table'] && $value['column'] == $search['column'] && $value['class'] == $search['class'])
              {
                return $key;
              }
          }else if(isset($search['table']) && isset($search['column'])){  
              if($value['table'] == $search['table'] && $value['column'] == $search['column'])
              {
                return $key;
              }
          }else if(isset($search['table'])){  
              if($value['table'] == $search['table'])
              {
                return $key;
              }
          }

        }
    }

    public function saveTargetMappingValues(Request $requestData) {
        try {
            $identityId = $requestData->identity_id;
            $identityTableId = $requestData->identity_table_id;
            $checkedDomNode = json_decode($requestData->checked_node);
            $mappingTargetData = RegexMapTargetFields::select('regex_map_target_fields.*')
                ->where('regex_map_target_fields.identity_table_id', $identityTableId)
                ->where('regex_map_target_fields.identity_id', $identityId)
                ->where('regex_map_target_fields.mapping_table', '!=', '')
                ->where('regex_map_target_fields.mapping_value_column', '!=', '')
                ->get();

            // CREATE ARRAY FOR MAPPING TABLE
            
            $mappingClassData = array();
            $mappingData = array();
            foreach($mappingTargetData as $mapData) {
                $matchflag = False;   
                if(array_key_exists($mapData->mapping_table,$mappingClassData)){
                if($mappingClassData[$mapData->mapping_table] == $mapData->mapping_value_column){
                    $matchflag = True;
                }else{
                    $matchflag = False;
                }
                }else{
                    $mappingClassData[$mapData->mapping_table] = $mapData->mapping_value_column;
                    $matchflag = True;   
                }   

                if($matchflag){
                    $mappingData[] = array(
                        'class' => $mapData->dom_class,
                        'table' => $mapData->mapping_table,
                        'column' => $mapData->mapping_value_column,
                        'primary_id' => 0
                    );   
                }                 
            } 
             
            // GET SOCIAL INFORMATION

            $socialName = array();
            $socialData = Identity_social::select('social.social_id','identity_social.identity_code')
                                    ->join('social','identity_social.identity_id','social.identity_id')
                                    ->get();

            foreach ($socialData as $social) {
                $socialName[$social->identity_code] = $social->social_id;
            }

            if (count($mappingTargetData) > 0) {
                $checkedGroupData = [];
                $peopleTargetTable = "";
                foreach ($checkedDomNode as $nodeData) {
                    $checkedGroupData[$nodeData->rootParent][] = $nodeData;
                }

                foreach ($checkedGroupData as $groupNodeData) {
                    $peoplePrimaryId = 0;                
                    $peopleTargetTable ="";
                    foreach ($groupNodeData as $checkedNode) {
                        $mappingField = $checkedNode->field;
                        if($mappingField == 'name') {
                            $mappingValue = $checkedNode->value;
                            foreach ($mappingTargetData as $mappingEachField) {
                                $domClass = $mappingEachField->dom_class;
                                if($domClass == 'name') {
                                    $mappingTable = $mappingEachField->mapping_table;
                                    $mappingForeignColumn = $mappingEachField->mapping_foreign_column;
                                    $mappingValueColumn = $mappingEachField->mapping_value_column;
                                    $targetTable = $mappingEachField->target_table;

                                    $existMappingData = DB::table($mappingTable)
                                        ->where($mappingValueColumn, $mappingValue)
                                        ->get()->first();
                                    if(!$existMappingData) {
                                        $mappingId = DB::table($mappingTable)->insertGetId(
                                            [$mappingValueColumn => $mappingValue]
                                        );
                                    }else{
                                        $primaryKey = $this->getTablePrimaryKey($mappingTable);
                                        $mappingId = $existMappingData->$primaryKey;
                                    }

                                    // CHECK TARGET TABLE EXIST OR NOT FOR SET PEOPLE TARGET TABLE
                                    if(empty($targetTable)){
                                        $peopleTargetTable = $mappingTable;
                                        $peoplePrimaryId = $mappingId;

                                    }else{

                                        // CHECK TRAGET TABLE EXIST RECORD 
                                        $existTargetData = DB::table($targetTable)
                                        ->where($mappingForeignColumn, $mappingId)
                                        ->get()->first();

                                        if(!$existTargetData) {
                                            $peopleTargetTable = $targetTable;
                                            $peoplePrimaryId = DB::table($targetTable)->insertGetId(
                                                [$mappingForeignColumn => $mappingId]
                                            );                                      
                                        }else{
                                            $peopleTargetTable = $targetTable;
                                            $primaryKey = $this->getTablePrimaryKey($targetTable);
                                            $peoplePrimaryId = $existTargetData->$primaryKey;
                                        }
                                    }

                                    $searchData = array(
                                        'table'     => $mappingTable,
                                        'column'    => $mappingValueColumn,
                                        'class'     => $domClass
                                    );

                                    // CHECK MAPPING TABLE KEY EXIST IN MAPPING DATA 
                                    $searchIndex = $this->multi_array_search($mappingData,$searchData);

                                    $mappingData[$searchIndex]['primary_id'] = $mappingId;
                                    break;
                                }
                            }
                            break;
                        }
                    } 

                    foreach ($groupNodeData as $checkedNode) {
                        $socialId = 0;
                        $mappingField = $checkedNode->field;

                        if($mappingField != 'name') {
                            $mappingValue = $checkedNode->value;                        
                            foreach ($mappingTargetData as $mappingEachField) {
                                $domClass = $mappingEachField->dom_class;
                                if($domClass == $mappingField) {
                                    $mappingTable = $mappingEachField->mapping_table;
                                    $mappingForeignColumn = $mappingEachField->mapping_foreign_column;
                                    $mappingValueColumn = $mappingEachField->mapping_value_column;
                                    $targetTable = $mappingEachField->target_table;

                                    $searchData = array(
                                        'table'     => $mappingTable,
                                        'column'    => $mappingValueColumn,
                                        'class'     => $domClass
                                    );

                                    // CHECK MAPPING TABLE KEY EXIST IN MAPPING DATA 
                                    $searchIndex = $this->multi_array_search($mappingData,$searchData);

                                    if($searchIndex != ""){ 
                                        // GET FORIGNKEY'S DATA FOR MAPPING TABLE
                                        $mappingTableForeignKeys = $this->getTableForeignKey($mappingTable);

                                        // CHECK MAPPING FIELD IS EXIST IN SOCIAL

                                        if (array_key_exists($mappingField,$socialName)){
                                            $socialId = $socialName[$mappingField];
                                        }   

                                        // HERE NEED TO CHECK MAPPING TABLE REFERENCE TO PEOPLE TARGET TABLE

                                        $existMappingData = DB::table($mappingTable)
                                            ->where($mappingValueColumn, $mappingValue)
                                            ->get()->first();

                                        if(!$existMappingData) {

                                            $mappingTableForeignKeysData = array();

                                            // CHECK MAPPING TABLE HAVE FOREIGN KEYS

                                            if(count($mappingTableForeignKeys) > 0){

                                                foreach ($mappingTableForeignKeys as $tableName => $foreignKeyField) {
                                                    if($tableName == $peopleTargetTable){
                                                        $mappingTableForeignKeysData[$foreignKeyField] = $peoplePrimaryId;
                                                    }else if ($tableName == self::SOCIAL_SCHEMA_NAME){
                                                        $mappingTableForeignKeysData[$foreignKeyField] = $socialId;
                                                    }else{
                                                        $mappingTableForeignKeysData[$foreignKeyField] = 0;
                                                    }
                                                }
                                            }

                                            $mappingTableForeignKeysData[$mappingValueColumn] = $mappingValue;

                                            $mappingId = DB::table($mappingTable)->insertGetId($mappingTableForeignKeysData);

                                        }else{
                                            $primaryKey = $this->getTablePrimaryKey($mappingTable);
                                            $mappingId = $existMappingData->$primaryKey;
                                        }                               


                                        // INSERT RECORD IF TARGET TABLE NOT EMPTY
                                        if(!empty($targetTable)){

                                            // GET FORIGNKEY'S DATA FOR TARGET TABLE
                                            $targetTableForeignKeys = $this->getTableForeignKey($targetTable);

                                            $targetTableForeignKeysData = array();

                                            // CHECK TARGET TABLE HAVE FOREIGN KEYS

                                            if(count($targetTableForeignKeys) > 0){
                                                foreach ($targetTableForeignKeys as $tableName => $foreignKeyField) {
                                                    if($tableName == $peopleTargetTable){
                                                        $targetTableForeignKeysData[$foreignKeyField] = $peoplePrimaryId;
                                                    }else if ($tableName == self::SOCIAL_SCHEMA_NAME){
                                                        $targetTableForeignKeysData[$foreignKeyField] = $socialId;
                                                    }else{
                                                        $targetTableForeignKeysData[$foreignKeyField] = 0;
                                                    }
                                                }
                                            }

                                            $targetTableForeignKeysData[$mappingForeignColumn] = $mappingId;

                                            DB::table($targetTable)->insertGetId($targetTableForeignKeysData);
                                        }
                                    }else{
                                        $searchData = array(
                                            'table'     => $mappingTable,
                                        );
                                        $searchIndex = $this->multi_array_search($mappingData,$searchData);    

                                        $mappingId = $mappingData[$searchIndex]['primary_id'];
                                        $primaryKey = $this->getTablePrimaryKey($mappingTable);
                                        DB::table($mappingTable)
                                            ->where($primaryKey,$mappingId)
                                            ->update([$mappingValueColumn => $mappingValue]);
                                    }                                
                                }
                            }
                        }
                    }
                }            
            }

            return json_encode(array('type' => 'success','message' => 'Mapping successfully completed'));
        } catch (Exception $e) {
            return json_encode(array('type' => 'error','message' => $e->getMessage()));
        }            

    }

    public function savePrimitiveValues(Request $requestData){
        $websiteUri = $requestData->website_url;
        if (strpos($websiteUri, 'http://') === false && strpos($websiteUri, 'https://') === false) {
            $websiteUri = 'http://' . $websiteUri;
        }

        $website_url = preg_replace('#http://|https://|www.#', '', $websiteUri);
        $website_url        = $this->getwebsiteUrlJson($website_url);
        
        if (!is_dir(storage_path() . self::HTMLDOM_DIR)) {
            mkdir(storage_path() . self::HTMLDOM_DIR, 0777, true);
        }

        $jsonFilePath = storage_path() . self::HTMLDOM_DIR . $website_url . "primitive.json";

        $websiteJsonDetail = $requestData->primitive_node;
        file_put_contents($jsonFilePath, $websiteJsonDetail);

        if($requestData->flag == self::TRANSFORM_APPLY){
            $primitiveTransformNode = json_decode($requestData->primitive_node);
            foreach ($primitiveTransformNode as $nodeData) {
            	$primitiveHistoryExist = PrimitiveHistory::
    		                                where("identity_table_id",$requestData->identity_table_id)
    		                                ->where("identity_id",$requestData->identity_id)
    		                                ->where("node_id",$nodeData->nodeId)
    		                                ->get()
    		                                ->count();	                            

                if(!$primitiveHistoryExist){
            		$primitiveHistory = new PrimitiveHistory();	
            		$primitiveHistory->identity_table_id = $requestData->identity_table_id;
            		$primitiveHistory->identity_id 		 = $requestData->identity_id;
            		$primitiveHistory->node_id 			 = $nodeData->nodeId;
            		$primitiveHistory->original_value 	 = $nodeData->value;
            		$primitiveHistory->transform_value 	 = $nodeData->newValue;
            		$primitiveHistory->update_timestamp	 = date("Y-m-d H:i:s");
            		$primitiveHistory->save();
            	}	
            } 
        }                          

        return $websiteJsonDetail;              
    }

    public function applySplitOnNodeValue(Request $request){
        
        $htmlDomData = json_decode($request->html_node);
        $splitPrimitiveData = array();
        $regexSplit = RegexSplit::all();

        foreach ($regexSplit as $splitKey => $split) {
            $splitPrimitiveData[$splitKey] = array(
                "marker" => $split->marker,
                "node" => $split->node
            );

            $regexSplitPrimitive = RegexSplitPrimitive::
                                        where("split_id",$split->split_id)->get();

            $primitiveData = array();
            foreach ($regexSplitPrimitive as $primitiveKey => $splitPrimitive) {
                $primitiveData[$primitiveKey] = $splitPrimitive->toArray();                
            }

            $splitPrimitiveData[$splitKey]['child'] = $primitiveData;
        } 

        // CREATE REGEX SPLIT PRIMITIVE PATTERN ARRAY AS PER TYPE WISE
        
        $regexPrimitives = array();
        $regexSplitPrimitive = RegexSplitPrimitive::select("type_id")->distinct()->get();

        foreach ($regexSplitPrimitive as $type) {
            $regexPrimitive = RegexPrimitive::where("type_id",$type->type_id)->get();
            
            $primitivePattern = array();
            foreach ($regexPrimitive as $primitiveData) {
                  $primitivePattern[] = $primitiveData->pattern; 
            }

            if(count($primitivePattern) > 0){
                $regexPrimitives[$type->type_id] = $primitivePattern;
            }    
        }
        
        // CREATE ASSOCIATIVE ARRAY FOR HTMLDOM NODE VALUE
                
        $htmlDomNode = array();
        foreach ($htmlDomData as $nodeData) {
            if(!empty(trim($nodeData->value))){
                $htmlDomNode[$nodeData->nodeId] = trim($nodeData->value);     
            }      
        }

        // CHECK MARKER IN HTML DOM NODE VALUE
        $splitHtmlDomNode = array();
        foreach ($splitPrimitiveData as $splitPrimitivekey => $splitPrimitivevalue) {
            $searchFor       = $splitPrimitivevalue['marker'];
            $searchNodeLevel = $splitPrimitivevalue['node'];

            $filteredNodeArray = 
                array_filter($htmlDomNode, function($element) use($searchFor){
                  return isset($element) && $element == $searchFor;
                });

            // GET TARGET NODE VALUE USING FILTER NODE ID.

            foreach ($filteredNodeArray as $nodeKey => $nodeValue) {
                
                $targetNodeId = ($nodeKey +  $searchNodeLevel);
                $targetNodeValue = isset($htmlDomNode[$targetNodeId])?$htmlDomNode[$targetNodeId]:'';

                // CREATE LOOP FOR GET SPLIT PRIMITIVE RECORD FOR APPLY PRIMITIVE
                
                if (isset($splitPrimitivevalue["child"])){
                    foreach ($splitPrimitivevalue["child"] as $splitPrimitiveRecord) {
                        $delimiter     = $splitPrimitiveRecord["delimiter"];
                        $regexTypeId   = $splitPrimitiveRecord["type_id"];
                        $variableName  = $splitPrimitiveRecord["variable"]; 

                        if(!empty($delimiter) && !empty($targetNodeValue)){
                            $splitNodeValues = explode($delimiter,$targetNodeValue);

                            // APPLY PRIMITIVE PATTERN ON EACH SPLIT NODE VALUE
                            foreach ($splitNodeValues as $splitValue) {
                                $matchFlag = 0;
                                foreach ($regexPrimitives[$regexTypeId] as $primitivePattern) {
                                    if(substr($primitivePattern, 0, 1) != self::PATH_SLASH){
                                        $primitivePattern = self::PATH_SLASH.$primitivePattern;
                                    }
                                    if(substr($primitivePattern, -1, 1) != self::PATH_SLASH)
                                    {
                                        $primitivePattern = $primitivePattern.self::PATH_SLASH;
                                    }
                                    if (preg_match($primitivePattern,$splitValue,$match)){
                                        $matchFlag = 1;
                                        $splitHtmlDomNode[$targetNodeId][$variableName] = $splitValue;
                                        break;   
                                    }
                                }
                                if($matchFlag){
                                    break;
                                }     
                            }    
                        }else{
                            $htmlDomData[$targetNodeId]->field = $variableName;
                            $htmlDomData[$targetNodeId]->class_data = $variableName;
                        }
                    }    
                }
            }     

        }        

        // CREATE NEW JSON OBJECT FOR HTML DOM NODE

        $newHtmlDom = $htmlDomData;

        foreach ($htmlDomData as  $nodeData) {
            if (array_key_exists($nodeData->nodeId,$splitHtmlDomNode)){
                $parentNode = $nodeData->nodeId;
                $path = $nodeData->path.$parentNode.self::PATH_SLASH;

                $childCount = $nodeData->child;
                foreach ($splitHtmlDomNode[$nodeData->nodeId] as $domKey => $domValue){
                    $totalNode = count($newHtmlDom);

                    $found = array_filter(
                        $newHtmlDom,
                        function ($e) use ($domKey,$path){
                            if($e->class_data == $domKey && $e->path == $path){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    );

                    if(!$found){
                        $childNodeObject = array(
                            "tag" => "",
                            "ref_column" => "",
                            "ref_table" => "",
                            "ref_id" => "",
                            "nodeId" => $totalNode,
                            "parentNodeId" => $parentNode,
                            "path" => $path,
                            "hasChildren" => false,
                            "child" => 0,
                            "nodeIndex" => $childCount,
                            "identityTableId" => $request->identity_table_id,
                            "status" => "scraped",
                            "dom_changes" => "No",
                            "history_count" => 0,
                            "class_change_data" => 0,
                            "selected" => "",
                            "expanded" => self::EXPAND_TRUE,
                            "field" => $domKey,
                            "value" => $domValue,
                            "class_data" => $domKey
                        );

                        $newHtmlDom[] = (object)$childNodeObject;
                        $childCount++;
                    }    
                }

                $newHtmlDom[$parentNode]->child = $childCount;
                $newHtmlDom[$parentNode]->hasChildren = true;
                $newHtmlDom[$parentNode]->expanded = self::EXPAND_TRUE;

                // EXPLAND PATH FROM ROOT TO LEAF LEVEL NODE.
                $fullPath = explode("/", $path);
                foreach ($fullPath as $nodeId) {
                    if($nodeId != ""){
                        $newHtmlDom[$nodeId]->expanded = self::EXPAND_TRUE;
                    }
                }
            }
        }

        // UPDATE A JSON FILE WITH LATEST HTML DOM NODE CONTENT 

        $websiteUri = $request->website_url;
        if (strpos($websiteUri, 'http://') === false && strpos($websiteUri, 'https://') === false) {
            $websiteUri = 'http://' . $websiteUri;
        }

        $website_url = preg_replace('#http://|https://|www.#', '', $websiteUri);
        $website_url = $this->getwebsiteUrlJson($website_url);
        $jsonFilePath = storage_path() . self::HTMLDOM_DIR . $website_url . ".all" . ".json";

        $updateNodeJsonDetail["regex_group"] = "";
        $updateNodeJsonDetail["scrapedDom"] = $newHtmlDom;
        
        file_put_contents($jsonFilePath, json_encode($updateNodeJsonDetail));        

        return $newHtmlDom;
    }
}
