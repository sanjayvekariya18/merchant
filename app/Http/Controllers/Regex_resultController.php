<?php
namespace App\Http\Controllers;

use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Identity_table_type;
use App\Identity_website;
use App\Regex_map_category;
use App\Regex_result_pattern;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Session;
use Redirect;
use Sunra\PhpSimple\HtmlDomParser;

include app_path() . '/Http/Controllers/simpleHtmlDom/HtmlDomParser.php';

class Regex_resultController extends PermissionsController
{
    const INIT_VALUE   = 0;
    const NULL_VALUE   = '';
    const RESULT_ID    = 'result_id';
    const RESULT_TEXT  = 'result_text';
    const PATTERN_ID   = 'pattern_id';
    const REGEX_RESULT = 'regex_result';

    public function __construct()
    {
        parent::__construct();
        $connectionStatus = ConnectionManager::setDbConfig('Regex_result');
        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
    }

    public function index()
    {
        return view('regex_result.index');
    }

    public function getWebsiteUrl(Request $request)
    {
        $identityTable = $request->table_code;
        $websiteLists  = DB::table($identityTable)
            ->select('identity_id', 'identity_website')
            ->where('identity_website', '!=', '')
            ->orderBy('identity_website', 'ASC')->get();
        return json_encode($websiteLists);
    }

    public function getIdentityTables()
    {
        $identityTables = Identity_table_type::select('type_id',
            'table_code')
            ->where('table_code', 'LIKE', '%identity_%')
            ->orderBy('table_code', 'DESC')->get();
        return json_encode($identityTables);
    }

    public function getSocialRegexPatterns($labelId)
    {
        $regexPatterns = Regex_map_category::select('regex_pattern.pattern_id',
            'regex_pattern.pattern',
            'regex_field.field_name')
            ->join('regex_pattern', 'regex_pattern.pattern_id', 'regex_map_category.regex_pattern_id')
            ->join('regex_field', 'regex_field.field_id', 'regex_map_category.regex_field_id')
            ->where('regex_map_category.regex_name_id', $labelId)
            ->get();
        return $regexPatterns;
    }

    public function getPatternRegexResult(Request $request)
    {
        $regexPatternResult = Regex_result_pattern::select('regex_result_pattern.*',
            'regex_pattern.pattern as regex_pattern',
            'identity_table_type.table_code as identity_table',
            'regex_map_category.regex_name_id',
            'regex_category_name.name as regex_name',
            'regex_field.field_name as regex_field')
            ->join('regex_pattern', 'regex_pattern.pattern_id', 'regex_result_pattern.pattern_id')
            ->join('identity_table_type', 'identity_table_type.type_id', 'regex_result_pattern.identity_table_id')
            ->leftjoin('regex_map_category', 'regex_map_category.regex_pattern_id', 'regex_result_pattern.pattern_id')
            ->join('regex_category_name', 'regex_category_name.name_id', 'regex_map_category.regex_name_id')
            ->join('regex_field', 'regex_field.field_id', 'regex_map_category.regex_field_id')
            ->offset($request->skip)
            ->limit($request->take);

        if (($request->table_id != "" && $request->table_id > 0) && $request->identity_id == "" && $request->label_id == "") {
            $regexPatternResult->where('regex_result_pattern.identity_table_id', $request->table_id);
            $result['total'] = $regexPatternResult->count();
        } else if (($request->table_id != "" && $request->table_id > 0) && ($request->identity_id != "" && $request->identity_id > 0) && $request->label_id == "") {
            $regexPatternResult->where('regex_result_pattern.identity_table_id', $request->table_id)
                ->where('regex_result_pattern.identity_id', $request->identity_id);
            $result['total'] = $regexPatternResult->count();
        } else if (($request->table_id != "" && $request->table_id > 0) && $request->identity_id == "" && ($request->label_id != "" && $request->label_id > 0)) {
            $regexPatternResult->where('regex_result_pattern.identity_table_id', $request->table_id)
                ->where('regex_category_name.name_id', $request->label_id);
            $result['total'] = $regexPatternResult->count();
        } else if (($request->table_id != "" && $request->table_id > 0) && ($request->identity_id != "" && $request->identity_id > 0) && ($request->label_id != "" && $request->label_id > 0)) {
            $regexPatternResult->where('regex_result_pattern.identity_table_id', $request->table_id)
                ->where('regex_result_pattern.identity_id', $request->identity_id)
                ->where('regex_category_name.name_id', $request->label_id);
            $result['total'] = $regexPatternResult->count();
        } else {
            $result['total'] = Regex_result_pattern::count();
        }
        $result['regex_result'] = $regexPatternResult->get();
        foreach ($result['regex_result'] as $key => $resultData) {
            $identityTable = $resultData->identity_table;
            $websiteUrl    = DB::table($identityTable)
                ->select('identity_website')
                ->where('identity_id', $resultData->identity_id)
                ->get()->first();
            $identityWebsite = $websiteUrl->identity_website;
            if (strpos($identityWebsite, 'http://') === false && strpos($identityWebsite, 'https://') === false) {
                $identityWebsite = 'http://' . $websiteUrl->identity_website;
            } else {
                $identityWebsite = $websiteUrl->identity_website;
            }
            $result['regex_result'][$key]->website_url = $identityWebsite;
        }
        return json_encode($result);
    }

    public function scrapeSocialLinks(Request $request)
    {
        $websiteUri      = $request->website_uri;
        $identityTableId = $request->identity_table;
        $identityId      = $request->identity_id;
        $labelId         = $request->label_id;
        if (strpos($websiteUri, 'http://') === false && strpos($websiteUri, 'https://') === false) {
            $websiteUri = 'http://' . $request->website_uri;
        } else {
            $websiteUri = $request->website_uri;
        }
        $eventHtml = HtmlDomParser::file_get_html($websiteUri);
        if ($eventHtml) {
            $socialRegexPatterns = $this->getSocialRegexPatterns($labelId);
            foreach ($socialRegexPatterns as $socialRegex) {
                preg_match_all($socialRegex->pattern, $eventHtml, $regexMatchData);
                $regexResultArray   = [];
                $socialUrl          = $regexMatchData[self::INIT_VALUE];
                $urlCount           = count($socialUrl);
                $patternId          = $socialRegex->pattern_id;
                $regexResultPattern = Regex_result_pattern::select(self::RESULT_ID, self::RESULT_TEXT)->where(self::PATTERN_ID, $patternId)->get();
                $regexPatternArray  = [];
                foreach ($regexResultPattern as $resultKey => $resultValue) {
                    $resultId                     = $resultValue[self::RESULT_ID];
                    $regexPatternArray[$resultId] = $resultValue[self::RESULT_TEXT];
                }
                for ($initData = self::INIT_VALUE; $initData < $urlCount; $initData++) {
                    $searchArray = array('href="', 'src="', '"');
                    $socialLink  = str_replace($searchArray, self::NULL_VALUE, $socialUrl[$initData]);
                    if (($socialLink != self::NULL_VALUE) && ($socialLink != 'www.') && !in_array($socialLink, $regexResultArray)) {
                        $regexResultArray[] = $socialLink;
                        $regexResultId      = array_search($socialLink, $regexPatternArray);
                        if ($regexResultId) {
                            $regex_result_pattern                    = Regex_result_pattern::findOrfail($regexResultId);
                            $regex_result_pattern->identity_table_id = $request->identity_table;
                            $regex_result_pattern->identity_id       = $request->identity_id;
                            $regex_result_pattern->result_text       = $socialLink;
                            $regex_result_pattern->save();
                        } else {
                            $regex_result_pattern                    = new Regex_result_pattern();
                            $regex_result_pattern->identity_table_id = $request->identity_table;
                            $regex_result_pattern->identity_id       = $request->identity_id;
                            $regex_result_pattern->pattern_id        = $patternId;
                            $regex_result_pattern->result_text       = $socialLink;
                            $regex_result_pattern->save();
                        }
                    }
                }
            }
        }
        return redirect(self::REGEX_RESULT);
    }
}
