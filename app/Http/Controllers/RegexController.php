<?php
namespace App\Http\Controllers;

use App\Helpers\ConnectionManager;
use App\Http\Controllers\PermissionsController;
use App\Http\Traits\PermissionTrait;
use App\Languages;
use App\RegexCategoryName;
use App\RegexField;
use App\RegexType;
use App\RegexFieldList;
use App\Regex_map_category;
use App\Regex_map_identity;
use App\Regex_pattern;
use App\RegexTableAccess;
use App\RegexTypeList;
use App\RegexPrimitive;
use App\RegexSplit;
use App\RegexSplitPrimitive;
use App\Module;
use App\ModuleFields;
use App\Identity_group_list;
use App\Identity_table_type;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Redirect;

class RegexController extends PermissionsController
{
    const FIRST_VALUE    = 1;
    const INIT_VALUE     = 0;
    const ADMIN_USER_ID  = 1;
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();
        $connectionStatus = ConnectionManager::setDbConfig('Regex');
        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
    }

    public function index()
    {
        if ($this->permissionDetails('Regex', 'access')) {
            $regexSplits = RegexSplit::all();
            $regexTypes = RegexType::all();
            return view('regex_setup.index',compact("regexTypes","regexSplits"));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
    public function regexMapCategoryFieldList(Request $request)
    {
        $type_id           = $request->type_id;
        $regex_fields_list = DB::table('regex_type_list')->where('type_id', '=', $type_id)->get();
        $regexFieldArray   = array();
        foreach ($regex_fields_list as $key => $value) {
            $regexFieldArray[] = $value->field_id;
        }
        $regex_fields = RegexField::whereIn('field_id', $regexFieldArray)->get();
        return json_encode($regex_fields);
    }
    public function regexMapCategoryTypeList(Request $request)
    {
        $name_id         = $request->name_id;
        $regex_type_list = DB::table('regex_field_list')->where('field_id', '=', $name_id)->get();
        $regexTypeArray  = array();
        foreach ($regex_type_list as $key => $value) {
            $regexTypeArray[] = $value->type_name;
        }
        $regex_types = RegexType::where('parent_id', '>', self::INIT_VALUE)->whereIn('type_name', $regexTypeArray)->get();
        return json_encode($regex_types);
    }
    public function store(Request $request)
    {
        $patterns  = explode(',', $request->pattern_id);
        $dataArray = array();
        foreach ($patterns as $key => $patternId) {

            $regexInfo = Regex_map_category::
                where("regex_type_id", $request->type_id)
                ->where("regex_name_id", $request->name_id)
                ->where("regex_field_id", $request->field_id)
                ->where("regex_pattern_id", $patternId)
                ->get()->first();

            if (!$regexInfo) {
                $dataArray[] = array(
                    'regex_type_id'    => $request->type_id,
                    'regex_name_id'    => $request->name_id,
                    'regex_field_id'   => $request->field_id,
                    'regex_pattern_id' => $patternId,
                );
                Regex_map_category::insert($dataArray);
                return array("type" => "success", "message" => 'Regex Map Category');
            }
            else {
                return array("type" => "error", "message" => 'Regex Map Category Already Inserted');
            }
        }
    }
    public function getReferenceTableList()
    {
        $referenceTableList = RegexTableAccess::get();
        return json_encode($referenceTableList);
    }
    public function getReferenceUsersTableList(){
        $referenceUserTableList = RegexTableAccess::where('group_id','=',$this->roleId)->get();
        return json_encode($referenceUserTableList);

    }
    public function getReferenceColumn()
    {
        $referenceColumnList = RegexTableAccess::select('column_name')->distinct()->get();
        return json_encode($referenceColumnList);
    }
    public function getRegexTypes(Request $request)
    {
        if (isset($request->categoryFilterValue) && !empty($request->categoryFilterValue)) {
            $regex_types = RegexType::
                leftjoin('regex_field_list', 'regex_field_list.type_name', 'regex_type.type_name')
                ->leftjoin('regex_category_name', 'regex_category_name.name_id', 'regex_field_list.field_id')
                ->where('regex_type.type_name', '!=', 'None')
                ->where('regex_category_name.name', '=', $request->categoryFilterValue)
                ->select('regex_type.*', 'regex_field_list.field_id', 'regex_category_name.name')
                ->get();

        } elseif (isset($request->name_id) && $request->name_id != 'undefined') {
            $name_id         = $request->name_id;
            $regex_type_list = DB::table('regex_field_list')->where('field_id', '=', $name_id)->get();
            $regexTypeArray  = array();
            foreach ($regex_type_list as $key => $value) {
                $regexTypeArray[] = $value->type_name;
            }
            $regex_types = RegexType::where('parent_id', '>', self::INIT_VALUE)->whereIn('type_name', $regexTypeArray)->get();
        } else {
            $regex_types = RegexType::where('parent_id', '>', self::INIT_VALUE)->get();
        }
        return json_encode($regex_types);
    }

    public function getRegexFields(Request $request)
    {
        if (isset($request->regexTypeFilterValue) && !empty($request->regexTypeFilterValue)) {
            $regex_fields = RegexField::
                leftjoin('regex_type_list', 'regex_type_list.field_id', 'regex_field.field_id')
                ->leftjoin('regex_type', 'regex_type.type_id', 'regex_type_list.type_id')
                ->where('regex_type.type_name', '!=', 'None')
                ->where('regex_type.type_name', '=', $request->regexTypeFilterValue)
                ->select('regex_field.*', 'regex_type_list.type_id', 'regex_type.type_name')->get();
        } elseif (isset($request->type_name) && !strcmp($request->type_name, "undefined") == self::INIT_VALUE) {
            $typeDetails       = RegexType::select('type_id')->where('type_name', $request->type_name)->first();
            $type_id           = $typeDetails->type_id;
            $regex_fields_list = DB::table('regex_type_list')->where('type_id', '=', $type_id)->get();
            $regexFieldArray   = array();
            foreach ($regex_fields_list as $key => $value) {
                $regexFieldArray[] = $value->field_id;
            }
            $regex_fields = RegexField::whereIn('field_id', $regexFieldArray)->get();
        } else {
            $regex_fields = RegexField::all();
        }
        return json_encode($regex_fields);
    }

    public function getRegexCategories()
    {
        $regex_categories = RegexCategoryName::orderBy('name_id', 'asc')->get();
        return json_encode($regex_categories);
    }

    public function getLanguages()
    {
        $languages = Languages::all();
        return json_encode($languages);
    }
    public function updateIncellRegexPattern(Request $request)
    {
        $timeZoneId = PermissionTrait::getTimeZoneId();
        $type_id    = self::INIT_VALUE;
        $field_id   = self::INIT_VALUE;
        if ($request->pattern_id == self::INIT_VALUE) {
            try {
                if (isset($request->pattern) && !!$request->pattern) {
                    $regex_pattern = new Regex_pattern;
                    if (isset($request->type_name) && !!$request->type_name) {
                        $typeDetails            = RegexType::select('type_id')->where('type_name', $request->type_name)->first();
                        $regex_pattern->type_id = $typeDetails->type_id;
                    }
                    if (isset($request->pattern)) {
                        $regex_pattern->pattern = $request->pattern;
                    }
                    if (isset($request->pattern_format)) {
                        $regex_pattern->pattern_format = $request->pattern_format;
                    }
                    if (isset($request->pattern_example)) {
                        $regex_pattern->pattern_example = $request->pattern_example;
                    }
                    $regex_pattern->language_id      = $request->language_id;
                    $regex_pattern->pattern_interval = $request->pattern_interval;
                    $regex_pattern->update_timezone  = $timeZoneId;
                    $regex_pattern->update_time      = time();
                    $regex_pattern->change_date      = date('Ymd');
                    $regex_pattern->setup_type_id    = $request->setup_type_id;
                    $regex_pattern->save();
                    $patternId = $regex_pattern->pattern_id;

                    $regex_map_category         = new Regex_map_category();
                    $regex_map_category->ref_id = self::INIT_VALUE;
                    if (isset($request->type_name) && !!$request->type_name) {
                        $typeDetails                       = RegexType::select('type_id')->where('type_name', $request->type_name)->first();
                        $regex_map_category->regex_type_id = $typeDetails->type_id;
                    }
                    $regex_map_category->regex_pattern_id = $patternId;
                    if (isset($request->name_id)) {
                        $regex_map_category->regex_name_id = $request->name_id;
                    }
                    if (isset($request->field_name) && !empty($request->field_name)) {
                        $fieldDetails                       = RegexField::select('field_id')->where('field_name', $request->field_name)->first();
                        $regex_map_category->regex_field_id = $fieldDetails->field_id;
                    }
                    if (isset($request->ref_class)) {
                        $regex_map_category->ref_class = $request->ref_class;
                    }
                    if (isset($request->ref_table)) {
                        $regex_map_category->ref_table = $request->ref_table;
                    }
                    if (isset($request->ref_column)) {
                        $regex_map_category->ref_column = $request->ref_column;
                    }
                    if (isset($request->ref_class) && isset($request->ref_table) && isset($request->ref_column)) {
                        $reference_id = DB::table($regex_map_category->ref_table)->select('identity_id')->where($regex_map_category->ref_column, $regex_map_category->ref_class)->first();
                        if (isset($reference_id->identity_id)) {
                            $regex_map_category->ref_id = $reference_id->identity_id;
                        }
                    }
                    $regex_map_category->save();
                    return array("type" => "success", "message" => 'Regex Pattern Inserted');
                }
            } catch (\Exception $e) {

                $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
                return array("type" => "error" , "message" => $exceptionMessage);
            }
        }
        try {
            $regex_pattern = Regex_pattern::findOrFail($request->pattern_id);
            if (isset($request->type_name) && !!$request->type_name) {
                $typeDetails = RegexType::select('type_id')->where('type_name', $request->type_name)->first();
                $type_id     = $typeDetails->type_id;
            }
            if (isset($request->field_name) && !empty($request->field_name)) {
                $fieldDetails = RegexField::select('field_id')->where('field_name', $request->field_name)->first();
                $field_id     = $fieldDetails->field_id;
            }
            $regex_pattern->type_id          = $type_id;
            $regex_pattern->pattern          = $request->pattern;
            $regex_pattern->pattern_format   = $request->pattern_format;
            $regex_pattern->pattern_example  = $request->pattern_example;
            $regex_pattern->language_id      = $request->language_id;
            $regex_pattern->pattern_interval = $request->pattern_interval;
            $regex_pattern->update_timezone  = $timeZoneId;
            $regex_pattern->update_time      = time();
            $regex_pattern->change_date      = date('Ymd');
            $regex_pattern->save();

            if (!empty($request->field_name) || !empty($request->name_id) || !empty($request->type_name) || !empty($request->ref_class) || !empty($request->ref_table) || !empty($request->ref_column)) {

                $regexMapFields = Regex_map_category::where("regex_pattern_id", $request->pattern_id)->get()->first();
                if ($regexMapFields) {
                    $regex_map_category                 = Regex_map_category::findOrfail($regexMapFields->mapping_id);
                    $regex_map_category->ref_id         = 0;
                    $regex_map_category->regex_type_id  = $type_id;
                    $regex_map_category->regex_name_id  = $request->name_id;
                    $regex_map_category->regex_field_id = $field_id;
                    $regex_map_category->ref_class      = $request->ref_class;
                    $regex_map_category->ref_table      = $request->ref_table;
                    $regex_map_category->ref_column     = $request->ref_column;
                    if (isset($request->ref_class) && isset($request->ref_table) && isset($request->ref_column)) {
                        $reference_id = DB::table($regex_map_category->ref_table)->select('identity_id')->where($regex_map_category->ref_column, $regex_map_category->ref_class)->first();
                        if (isset($reference_id->identity_id)) {
                            $regex_map_category->ref_id = $reference_id->identity_id;
                        }
                    }
                    $regex_map_category->save();
                    return array("type" => "success", "message" => 'Regex Pattern Updated');
                } else {
                    $regex_map_category                   = new Regex_map_category();
                    $regex_map_category->ref_id           = 0;
                    $regex_map_category->regex_type_id    = $type_id;
                    $regex_map_category->regex_pattern_id = $request->pattern_id;
                    $regex_map_category->regex_name_id    = $request->name_id;
                    $regex_map_category->regex_field_id   = $field_id;
                    $regex_map_category->ref_class        = $request->ref_class;
                    $regex_map_category->ref_table        = $request->ref_table;
                    $regex_map_category->ref_column       = $request->ref_column;
                    if (isset($request->ref_class) && isset($request->ref_table) && isset($request->ref_column)) {
                        $reference_id = DB::table($regex_map_category->ref_table)->select('identity_id')->where($regex_map_category->ref_column, $regex_map_category->ref_class)->first();
                        if (isset($reference_id->identity_id)) {
                            $regex_map_category->ref_id = $reference_id->identity_id;
                        }
                    }
                    $regex_map_category->save();
                    return array("type" => "success", "message" => 'Regex Pattern Updated');
                }
            }
        } catch (\Exception $e) {

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return array("type" => "error" , "message" => $exceptionMessage);
        }
    }

    public function updateIncellRegexPrimitive(Request $request)
    {
        try {
            if (isset($request->pattern) && !!$request->pattern) {

                if($request->pattern_id == 0){
                    $regex_premitive = new RegexPrimitive();
                }else{
                    $regex_premitive = RegexPrimitive::findOrfail($request->pattern_id);
                }

                $request->pattern = $request->pattern;
                if (substr($request->pattern, 0, 1) != "/") {
                    $request->pattern = "/" . $request->pattern;
                }
                if (substr($request->pattern, -1, 1) != "/") {
                    $request->pattern = $request->pattern . "/";
                }
                if (@preg_match($request->pattern, null) === false) {
                    return array("type" => "error", "message" => 'Regex Pattern is not valid');

                } else {

                    if (isset($request->type_name) && !!$request->type_name) {
                        $typeDetails              = RegexType::select('type_id')->where('type_name', $request->type_name)->first();
                        $regex_premitive->type_id = $typeDetails->type_id;
                    }
                    if (isset($request->pattern)) {
                        $regex_premitive->pattern = $request->pattern;
                    }

                    if (isset($request->primitive_code)) {
                        $regex_premitive->primitive_code = $request->primitive_code;
                    }

                    if (isset($request->language_id) && $request->language_id != "None") {
                        $regex_premitive->language_id = $request->language_id;
                    }

                    $regex_premitive->save();

                    return array("type" => "success", "message" => 'Regex Premitive Information Updated');
                }
            }
        } catch (\Exception $e) {

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return array("type" => "error" , "message" => $exceptionMessage);
        }
    }


    public function deleteRegexPattern(Request $request)
    {
        try {
            $regex_pattern = Regex_pattern::findOrFail($request->pattern_id);
            $regex_pattern->delete();

            $regexMapFields = Regex_map_category::where("regex_pattern_id", $request->pattern_id)->first();
            if ($regexMapFields) {
                $regex_map_category = Regex_map_category::findOrFail($regexMapFields->mapping_id);
                $regex_map_category->delete();
            }

            $mapIdentityInfo = Regex_map_identity::where("regex_pattern_id", $request->pattern_id)->get();
            if (count($mapIdentityInfo) > 0) {
                foreach ($mapIdentityInfo as $blockValue) {
                    $regex_map_identity                   = Regex_map_identity::findOrfail($blockValue->regex_id);
                    $regex_map_identity->regex_pattern_id = 0;
                    $regex_map_identity->update_date      = date('Ymd');
                    $regex_map_identity->update_time      = time();
                    $regex_map_identity->save();
                    return $regex_map_identity;
                }
            }
        } catch (\Exception $e) {
            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return (array("error" => $exceptionMessage));
        }
    }

    public function deleteRegexPrimitive(Request $request)
    {
        try {
            $regexPrimitive = RegexPrimitive::findOrFail($request->pattern_id);
            $regexPrimitive->delete();

        } catch (\Exception $e) {
            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return (array("error" => $exceptionMessage));
        }
    }

    public function getRegexPatterns(Request $request)
    {
        $total_records = 0;
        $regexQuery    = Regex_pattern::
            select('regex_pattern.*', 'languages.language_code', 'regex_type.type_name', 'regex_field.*', 'regex_category_name.*', 'regex_map_category.*', 'Regex_table_access.table_id')
            ->leftjoin('regex_type', 'regex_pattern.type_id', 'regex_type.type_id')
            ->join('languages', 'languages.language_id', 'regex_pattern.language_id')
            ->leftjoin('regex_map_category', 'regex_map_category.regex_pattern_id', 'regex_pattern.pattern_id')
            ->leftjoin('regex_field', 'regex_field.field_id', 'regex_map_category.regex_field_id')
            ->leftjoin('Regex_table_access', 'Regex_table_access.table_name', 'regex_map_category.ref_table')
            ->leftjoin('regex_category_name', 'regex_category_name.name_id', 'regex_map_category.regex_name_id')
            ->where('setup_type_id',$request->setup_type_id)
            ->orderBy('pattern_id', 'DESC')->groupBy('pattern_id');
        if (isset($request->filter['filters'])) {
            $actionFilter = $request->filter;
            $filterField  = $actionFilter['filters'][0]['field'];
            if ($filterField == 'name_id') {
                $filterField = 'regex_category_name.name';
            } elseif ($filterField == 'type_name') {
                $filterField = 'regex_type.type_name';
            } elseif ($filterField == 'field_name') {
                $filterField = 'regex_field.field_name';
            } else {
                $filterField = 'regex_map_category.ref_table';
            }
            $filterValue = $request->filter['filters'][0]['value'];
            $regexQuery->where($filterField, '=', $filterValue);
        }
        $total_records = $total_records + $regexQuery->get()->count();
        if (isset($request->take)) {
            $regexQuery->offset($request->skip)->limit($request->take);
        }
        $regex_patterns = $regexQuery->get();
        foreach ($regex_patterns as $key => $regex_pattern_value) {
            if (isset($regex_pattern_value['ref_table'])) {
                $regex_patterns[$key]->ref_table = $regex_pattern_value['ref_table'];
            } else {
                $regex_patterns[$key]->ref_table = "None";
            }
            if (isset($regex_pattern_value['ref_column'])) {
                $regex_patterns[$key]->ref_column = $regex_pattern_value['ref_column'];
            } else {
                $regex_patterns[$key]->ref_column = "None";
            }
        }
        $result['total']              = $total_records;
        $result['regex_patterns']     = $regex_patterns->toArray();
        $regexPatternAddNewDetails[0] = array("pattern_id" => 0, "type_id" => '', "pattern" => "", "pattern_format" => "", "pattern_example" => null, "language_id" => "None", "pattern_interval" => 0, "update_timezone" => self::FIRST_VALUE, "update_time" => "", "change_date" => "", "user_id" => 0, "language_code" => "None", "type_name" => "", "field_id" => '', "field_name" => "", "reference_table" => "identity_social", "reference_column" => "identity_code", "name_id" => '', "name" => "", "mapping_id" => '', "regex_type_id" => '', "regex_pattern_id" => '', "regex_name_id" => '', "regex_field_id" => '', "ref_class" => "", "ref_table" => "None", "ref_column" => "None", "ref_id" => 0);
        $result['regex_patterns']     = array_merge($regexPatternAddNewDetails, $result['regex_patterns']);
        return json_encode($result);
    }

    public function getRegexPrimitive(Request $request)
    {
        $total_records = 0;
        $regexQuery    = RegexPrimitive::
            select('regex_primitive.*', 'languages.language_code', 'regex_type.type_name')
            ->join('regex_type', 'regex_primitive.type_id', 'regex_type.type_id')
            ->join('languages', 'languages.language_id', 'regex_primitive.language_id');

        if (isset($request->filter['filters'])) {
            $actionFilter = $request->filter;
            $filterField  = $actionFilter['filters'][0]['field'];
            if ($filterField == 'type_name') {
                $filterField = 'regex_type.type_name';
            } 
            $filterValue = $request->filter['filters'][0]['value'];
            $regexQuery->where($filterField, '=', $filterValue);
        }
        $total_records = $total_records + $regexQuery->get()->count();
        if (isset($request->take)) {
            $regexQuery->offset($request->skip)->limit($request->take);
        }
        $regex_patterns = $regexQuery->get();
        
        $result['total']              = $total_records;
        $result['regex_patterns']     = $regex_patterns->toArray();
        $regexPatternAddNewDetails[0] = array("pattern_id" => 0, "type_id" => '', "pattern" => "", "language_id" => "None");
        $result['regex_patterns']     = array_merge($regexPatternAddNewDetails, $result['regex_patterns']);
        return json_encode($result);
    }

    public function getRegexDetail(Request $request)
    {
        $error = array();
        $curl  = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => $request->test_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_TIMEOUT        => 30000,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
        ));
        $contents = curl_exec($curl);
        $err      = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $error['error'] = "Couldn't fetch the URL.";
            return json_encode($error);
        }

        preg_match_all($request->regex_pattern, $contents, $result);
        if (count($result)) {
            return json_encode(array_slice($result[0], 0, 5, true));
        } else {
            return json_encode(array());
        }
    }

    public function updateVerifyStatus(Request $request)
    {
        try {
            $pattern              = Regex_pattern::findOrFail($request->pattern_id);
            $pattern->is_verified = ($request->type == "accept") ? 1 : 0;
            $pattern->save();
            return $pattern;
        } catch (\Exception $e) {

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return (array("error" => $exceptionMessage));
        }
    }

    public function getCategoryList(Request $request)
    {
        $total_records = 0;
        $regexCategory = RegexCategoryName::select('regex_category_name.name_id',
            'regex_category_name.name as category_name');
        if (isset($request->take)) {
            $regexCategory->offset($request->skip)->limit($request->take);
        }
        $total_records                    = $total_records + $regexCategory->count();
        $regex_category_list              = $regexCategory->get();
        $regex_category['total']          = $total_records;
        $regex_category['regex_category'] = $regex_category_list->toArray();
        $regexCategotyAddNewDetails[0]    = array("name_id" => 0, "category_name" => '');
        $regex_category['regex_category'] = array_merge($regexCategotyAddNewDetails, $regex_category['regex_category']);
        return json_encode($regex_category);
    }

    public function createCategory(Request $request)
    {
        try {
            $regex_category       = new RegexCategoryName();
            $regex_category->name = $request['category_name'];
            $regex_category->save();
            return $regex_category;
        } catch (\Exception $e) {

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return (array("error" => $exceptionMessage));
        }
    }

    public function updateCategory(Request $request)
    {
        if ($request->name_id == 0) {
            try {
                $regex_category       = new RegexCategoryName();
                $regex_category->name = $request['category_name'];
                $regex_category->save();
                return $regex_category;
            } catch (\Exception $e) {

                $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
                return (array("error" => $exceptionMessage));
            }
        } else {
            try {
                $regex_category       = RegexCategoryName::findOrfail($request->name_id);
                $regex_category->name = $request->category_name;
                $regex_category->save();
                return $regex_category;
            } catch (\Exception $e) {

                $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
                return (array("error" => $exceptionMessage));
            }
        }
    }

    public function deleteCategory(Request $request)
    {
        try {
            $regex_category = RegexCategoryName::findOrFail($request->name_id);
            $regex_category->delete();
            return $regex_category;
        } catch (\Exception $e) {

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return (array("error" => $exceptionMessage));
        }
    }
    public function getRegexFieldList(Request $request)
    {
        $total_records = 0;
        $regexField    = RegexField::
            leftjoin('regex_type_list', 'regex_type_list.field_id', 'regex_field.field_id')
            ->leftjoin('regex_type', 'regex_type.type_id', 'regex_type_list.type_id')
            ->select('regex_field.*', 'regex_type_list.type_id', 'regex_type.type_name');

        if (!empty($request->regexTypeFilterValue)) {
            $regexField->where('regex_type.type_name', '=', $request->regexTypeFilterValue);
        }
        if (isset($request->take)) {
            $regexField->offset($request->skip)->limit($request->take);
        }
        $total_records              = $total_records + $regexField->count();
        $regex_field_list           = $regexField->get();
        $regex_field['total']       = $total_records;
        $regex_field['regex_field'] = $regex_field_list->toArray();
        $regexFieldAddNewDetails[0] = array("field_id" => 0, "field_name" => '', "type_name" => 'Mud Course', "type_id" => 4);
        $regex_field['regex_field'] = array_merge($regexFieldAddNewDetails, $regex_field['regex_field']);
        return json_encode($regex_field);
    }
    public function createRegexField(Request $request)
    {
        try {
            $regex_field             = new RegexField();
            $regex_field->field_name = $request['field_name'];
            $regex_field->save();
            $regex_type_list           = new RegexTypeList();
            $regex_type_list->type_id  = $request['type_id']['type_id'];
            $regex_type_list->field_id = $regex_field->field_id;
            $regex_type_list->save();
            return $regex_field;
        } catch (\Exception $e) {

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return (array("error" => $exceptionMessage));
        }
    }
    public function updateRegexField(Request $request)
    {
        if ($request->field_id == 0) {
            try {
                $regex_field             = new RegexField();
                $regex_field->field_name = $request->field_name;
                $regex_field->save();
                $regex_type_list           = new RegexTypeList();
                $regex_type_list->type_id  = $request->type_id;
                $regex_type_list->field_id = $regex_field->field_id;
                $regex_type_list->save();
                return array("type" => "success", "message" => 'Regex Field Inserted');
            } catch (\Exception $e) {

                $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
                return array("type" => "error", "message" => $exceptionMessage);
            }
        } else {
            try {
                $regex_field             = RegexField::findOrfail($request->field_id);
                $regex_field->field_name = $request->field_name;
                $regex_field->save();

                if (isset($request->type_id)) {
                    $field_id = $request->field_id;
                    $type_id  = $request->type_id;
                    DB::table('regex_type_list')->where('field_id', '=', $field_id)->update(array('type_id' => $type_id));
                }
                return array("type" => "success", "message" => 'Regex Field Updated');
            } catch (\Exception $e) {

                $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
                return array("type" => "error", "message" => $exceptionMessage);
            }
        }
    }
    public function deleteRegexField(Request $request)
    {
        try {
            $regex_field = RegexField::findOrFail($request->field_id);
            $regex_field->delete();
            RegexTypeList::where('field_id', '=', $request->field_id)->delete();
            return $regex_field;
        } catch (\Exception $e) {

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return (array("error" => $exceptionMessage));
        }
    }

    public function getRegexTypeList(Request $request)
    {
        $total_records = 0;
        $regexType     = RegexType::
            leftjoin('regex_field_list', 'regex_field_list.type_name', 'regex_type.type_name')
            ->leftjoin('regex_category_name', 'regex_category_name.name_id', 'regex_field_list.field_id')
            ->where('regex_type.type_name', '!=', 'None')
            ->select('regex_type.*', 'regex_field_list.field_id', 'regex_category_name.name');

        if (!empty($request->categoryFilterValue)) {
            $regexType->where('regex_category_name.name', '=', $request->categoryFilterValue);
        }
        if (isset($request->take)) {
            $regexType->offset($request->skip)->limit($request->take);
        }
        $total_records             = $total_records + $regexType->count();
        $regex_type_list           = $regexType->get();
        $regex_type['total']       = $total_records;
        $regex_type['regex_type']  = $regex_type_list->toArray();
        $regexTypeAddNewDetails[0] = array("type_id" => 0, "type_name" => '', "name" => 'Social Links');
        $regex_type['regex_type']  = array_merge($regexTypeAddNewDetails, $regex_type['regex_type']);
        return json_encode($regex_type);
    }

    public function createRegexType(Request $request)
    {
        try {
            $regex_type            = new RegexType();
            $type_code             = preg_replace('/\s*/', '', $request['type_name']);
            $type_code             = strtolower($type_code);
            $regex_type->type_code = $type_code;
            $regex_type->type_name = $request['type_name'];
            $regex_type->save();

            $regex_field_list            = new RegexFieldList();
            $regex_field_list->type_id   = $regex_type->type_id;
            $regex_field_list->type_name = $request['type_name'];
            $nameList                    = RegexCategoryName::where("name", $request['name']['name'])->first();
            $regex_field_list->field_id  = $nameList->name_id;
            $regex_field_list->save();
            return $regex_type;
        } catch (\Exception $e) {

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return (array("error" => $exceptionMessage));
        }
    }

    public function updateRegexType(Request $request)
    {
        if ($request->type_id == 0) {
            try {
                $regex_type            = new RegexType();
                $type_code             = preg_replace('/\s*/', '', $request->type_name);
                $type_code             = strtolower($type_code);
                $regex_type->type_code = $type_code;
                $regex_type->type_name = $request->type_name;
                $regex_type->save();

                $regex_field_list            = new RegexFieldList();
                $regex_field_list->type_id   = $regex_type->type_id;
                $regex_field_list->type_name = $request->type_name;
                $nameList                    = RegexCategoryName::where("name", $request->name)->first();
                $regex_field_list->field_id  = $nameList->name_id;
                $regex_field_list->save();
                return $regex_field_list;
            } catch (\Exception $e) {

                $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
                return (array("error" => $exceptionMessage));
            }
        }
        try {
            $regex_type            = RegexType::findOrfail($request->type_id);
            $type_code             = preg_replace('/\s*/', '', $request->type_name);
            $type_code             = strtolower($type_code);
            $regex_type->type_code = $type_code;
            $regex_type->type_name = $request->type_name;
            $regex_type->save();

            $regex_list_id               = RegexFieldList::where("type_id", $regex_type->type_id)->first();
            $regex_field_list            = RegexFieldList::findOrfail($regex_list_id->regex_list_id);
            $regex_field_list->type_id   = $regex_type->type_id;
            $regex_field_list->type_name = $request['type_name'];
            if ($request->name == '') {
                $regex_field_list->field_id = $regex_list_id->field_id;
            } else {
                $nameList                   = RegexCategoryName::where("name", $request->name)->first();
                $regex_field_list->field_id = $nameList->name_id;
            }
            $regex_field_list->save();
            return $regex_type;
        } catch (\Exception $e) {

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return (array("error" => $exceptionMessage));
        }
    }

    public function deleteRegexType(Request $request)
    {
        try {
            $regex_type = RegexType::findOrFail($request->type_id);
            $regex_type->delete();
            RegexFieldList::where('type_id', '=', $request->type_id)->delete();
            return $regex_type;
        } catch (\Exception $e) {

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return (array("error" => $exceptionMessage));
        }

    }

    public function checkPatternTimeInterval()
    {
        $allRegexPattern = Regex_pattern::all();
        foreach ($allRegexPattern as $regexPattern) {
            $patternInterval = $regexPattern['pattern_interval'];
            $lastUpdateTime  = $regexPattern['update_time'];
            $lastUpdateDate  = $regexPattern['change_date'];
            $currentTime     = time();
            $timeDifference  = $currentTime - $lastUpdateTime;
            $hours           = round($timeDifference / 3600);
            if ($hours == $patternInterval) {

            }
        }
    }

    public function updateSplitData(Request $request){

        if($request->splitData["marker"] !="" && $request->splitData["node"] !=""){ 
           if($request->splitData["split_id"] !=""){
                $regexSplit = RegexSplit::findOrfail($request->splitData["split_id"]); 
           }else{
                $regexSplit = new RegexSplit();
           }
           
           $regexSplit->marker = $request->splitData["marker"];
           $regexSplit->node = $request->splitData["node"];
           $regexSplit->identity_id = $this->staffId;
           $regexSplit->identity_table_id = $this->identityTableId;
           $regexSplit->save();

            if(isset($request->splitData["child"])){
                RegexSplitPrimitive::where("split_id",$request->splitData["split_id"])->delete();
                foreach ($request->splitData["child"] as $splitPrimitive) {
                    if(!empty($splitPrimitive["type_id"]) && !empty($splitPrimitive["variable"])){
                        $regexSplitPrimitive = new RegexSplitPrimitive();
                        $regexSplitPrimitive->split_id = $regexSplit->split_id;
                        $regexSplitPrimitive->type_id = $splitPrimitive["type_id"];
                        $regexSplitPrimitive->delimiter = $splitPrimitive["delimiter"];
                        $regexSplitPrimitive->variable = $splitPrimitive["variable"];
                        $regexSplitPrimitive->save(); 
                    }
                }
            }  
            return json_encode(array("type" => "success","message" => "Information successfully updated")); 
        }else{
            return json_encode(array("type" => "error","message" => "Information not updated"));
        }
    }
    
    public function getRegexSplitData(){

        if($this->userId == self::ADMIN_USER_ID){
            $regexSplit = RegexSplit::all();
        }else{
            $groupIDList   = array();
            $userGroupList = Identity_group_list::
                where('identity_group_list.identity_id', $this->staffId)
                ->where('identity_group_list.identity_table_id', $this->identityTableId)
                ->select('identity_group_list.group_id')
                ->get();

            foreach ($userGroupList as $userGroup) {
                $groupIDList[] = $userGroup['group_id'];
            }

            $regexSplit = RegexSplit::
                                select("regex_split.*")
                                ->join('identity_group_list', function($join) use ($groupIDList){
                                    $join->on('identity_group_list.identity_id', '=', 'regex_split.identity_id')
                                         ->on('identity_group_list.identity_table_id', '=', 'regex_split.identity_table_id')
                                         ->whereIn('identity_group_list.group_id',$groupIDList);
                                    })
                                ->get();
        }      

        foreach ($regexSplit as $key => $value) {

            $originalTable = Identity_table_type::where("type_id",$value->identity_table_id)->get()->first()->table_code; 

            $originalTableData  = DB::table($originalTable)
                                    ->where("identity_id",$value->identity_id)
                                    ->get()
                                    ->first();

            $identityTable = Identity_table_type::where("type_id",$originalTableData->identity_table_id)->get()->first()->table_code;

            $identityTableData  = DB::table($identityTable)
                                    ->where("identity_id",$value->identity_id)
                                    ->get()
                                    ->first(); 
                                    
            $regexSplit[$key]->identity_name = $identityTableData->identity_name;
        } 

        return json_encode($regexSplit);                  
    }

    public function getRegexSplitPrimitiveData($split_id){
        $regexSplitPrimitive = RegexSplitPrimitive::
                            join("regex_type","regex_type.type_id","regex_split_primitive.type_id")
                            ->where("split_id",$split_id)
                            ->get();
        return json_encode($regexSplitPrimitive);                  
    }

    public function getSplitData(Request $request){
        
        $regexSplit = RegexSplit::
                            where("split_id",$request->split_id)
                            ->get()
                            ->first();

        $regexSplitPrimitive = RegexSplitPrimitive::
                            join("regex_type","regex_type.type_id","regex_split_primitive.type_id")
                            ->where("split_id",$request->split_id)
                            ->get();

        $regexSplitData['splitData'] = $regexSplit;
        $regexSplitData['splitPrimitiveData'] = $regexSplitPrimitive;

        return json_encode($regexSplitData);                    
    }

    public function deleteSplitData(Request $request){
        try{
            RegexSplit::where("split_id",$request->split_id)->delete();
            RegexSplitPrimitive::where("split_id",$request->split_id)->delete();
            return json_encode(array("type" => "success","message" => "Successfully deleted"));
        }catch (Exception $e){
            return json_encode(array("type" => "error","message" => $e->getMessage()));
        }
    }
}
