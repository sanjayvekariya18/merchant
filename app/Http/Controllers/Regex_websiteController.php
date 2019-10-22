<?php
namespace App\Http\Controllers;

use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Http\Traits\PermissionTrait;
use App\Identity_website;
use App\Regex_block_delimiter;
use App\Regex_block_element;
use App\Regex_block_level;
use App\Regex_map_identity;
use App\Regex_pagination;
use App\Regex_pattern;
use App\Regex_result_delimiter;
use App\RegexType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Session;
use Redirect;
use Sunra\PhpSimple\HtmlDomParser;

include app_path() . '/Http/Controllers/simpleHtmlDom/HtmlDomParser.php';

class Regex_websiteController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();
        $connectionStatus = ConnectionManager::setDbConfig('Regex_website');
        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
    }

    public function index()
    {
        if ($this->permissionDetails('Regex_website', 'access')) {
            return view('regex_website.index');
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getWebsiteList(Request $request)
    {
        $websiteLists = Identity_website::select('identity_website.identity_id',
            'identity_website.identity_website',
            'regex_map_identity.pagination_id',
            'regex_map_identity.regex_block_element_id',
            'regex_map_identity.regex_block_level_id',
            'regex_map_identity.regex_pattern_id',
            'regex_block_level.pattern as block_pattern')
            ->leftjoin('regex_map_identity', 'regex_map_identity.identity_id', 'identity_website.identity_id')
            ->leftjoin('regex_block_level', 'regex_block_level.id', 'regex_map_identity.regex_block_level_id')
            ->offset($request->skip)
            ->limit($request->take);

        if (isset($request->term) && trim($request->term) != "") {
            $websiteLists
                ->where('identity_website.identity_website', 'LIKE', '%' . $request->term . '%');
            $result['total'] = Identity_website::
                where('identity_website', 'LIKE', '%' . $request->term . '%')->count();
        } else {
            $result['total'] = Identity_website::count();
        }

        $result['regex_websites'] = $websiteLists->orderBy('identity_website', 'ASC')
            ->get();
        foreach ($result['regex_websites'] as $key => $websiteList) {
            $totalRegex                                      = Regex_map_identity::where('identity_id', $websiteList->identity_id)->where('regex_pattern_id', '!=', 0)->count();
            $totalBlock                                      = Regex_map_identity::where('identity_id', $websiteList->identity_id)->where('regex_block_level_id', '!=', 0)->count();
            $totalElement                                    = Regex_map_identity::where('identity_id', $websiteList->identity_id)->where('regex_block_element_id', '!=', 0)->count();
            $totalPagination                                 = Regex_map_identity::where('identity_id', $websiteList->identity_id)->where('pagination_id', '!=', 0)->count();
            $result['regex_websites'][$key]->totalRegex      = $totalRegex;
            $result['regex_websites'][$key]->totalBlock      = $totalBlock;
            $result['regex_websites'][$key]->totalElement    = $totalElement;
            $result['regex_websites'][$key]->totalPagination = $totalPagination;
        }
        return json_encode($result);
    }

    public function getRegexPatternList(Request $request)
    {
        $regexPatternQuery = Regex_pattern::select('regex_pattern.*',
            'regex_type.type_name')
            ->leftjoin('regex_type', 'regex_pattern.type_id', 'regex_type.type_id')
            ->offset($request->skip)
            ->limit($request->take);

        if ($request->type_id != "" && $request->type_id > 0) {
            $result['total'] = Regex_pattern::where('type_id', $request->type_id)->count();
            $regexPatternQuery->where('regex_pattern.type_id', $request->type_id);
        } else if (isset($request->search_field) && trim($request->search_field) != "") {
            $regexPatternQuery->where('regex_type.type_name', 'LIKE', '%' . $request->search_field . '%')
                ->orWhere('regex_pattern.pattern', 'LIKE', '%' . $request->search_field . '%');
            $result['total'] = $regexPatternQuery->count();
        } else {
            $result['total'] = Regex_pattern::count();
        }

        $result['regex_patterns'] = $regexPatternQuery->orderBy('pattern_id', 'DESC')->get();
        return json_encode($result);
    }

    public function getRegexTypes()
    {
        $regex_types = RegexType::where('parent_id', '>', 0)->get();
        return json_encode($regex_types);
    }

    public function saveWebsiteRegex(Request $request)
    {
        $identityId      = $request->identity_id;
        $mapIdentityInfo = Regex_map_identity::where("identity_id", $identityId)->get()->first();
        $timeZoneId      = PermissionTrait::getTimeZoneId();
        if ($mapIdentityInfo) {
            $regex_map_identity                   = Regex_map_identity::findOrfail($mapIdentityInfo->regex_id);
            $regex_map_identity->regex_pattern_id = $request->pattern_id;
            $regex_map_identity->update_timezone  = $timeZoneId;
            $regex_map_identity->update_date      = date('Ymd');
            $regex_map_identity->update_time      = time();
            $regex_map_identity->save();
        } else {
            $regex_map_identity                    = new Regex_map_identity();
            $regex_map_identity->identity_table_id = 56;
            $regex_map_identity->identity_id       = $identityId;
            $regex_map_identity->regex_pattern_id  = $request->pattern_id;
            $regex_map_identity->update_timezone   = $timeZoneId;
            $regex_map_identity->update_date       = date('Ymd');
            $regex_map_identity->update_time       = time();
            $regex_map_identity->status            = 1;
            $regex_map_identity->save();
        }
        return 1;
    }

    public function createWebsite(Request $request)
    {
        $website_url     = preg_replace('#http://|https://|www.#', '', $request['identity_website']);
        $website_id_list = Identity_website::where('identity_website', $website_url)->get()->first();
        if (!$website_id_list) {
            $identity_website                   = new Identity_website();
            $identity_website->identity_website = $website_url;
            $identity_website->save();
            $websiteIdentityId = $identity_website->identity_id;

            $timeZoneId                               = PermissionTrait::getTimeZoneId();
            $regex_map_identity                       = new Regex_map_identity();
            $regex_map_identity->identity_table_id    = 56;
            $regex_map_identity->identity_id          = $websiteIdentityId;
            $regex_map_identity->regex_block_level_id = $request['regex_block_level_id'];
            $regex_map_identity->update_timezone      = $timeZoneId;
            $regex_map_identity->update_date          = date('Ymd');
            $regex_map_identity->update_time          = time();
            $regex_map_identity->status               = 1;
            $regex_map_identity->save();
        }
        return 1;
    }

    public function updateWebsite(Request $request)
    {
        $website_url = preg_replace('#http://|https://|www.#', '', $request->identity_website);

        $identity_website                   = Identity_website::findOrfail($request->identity_id);
        $identity_website->identity_website = $website_url;
        $identity_website->save();

        $mapWebsiteInfo = Regex_map_identity::where("identity_id", $request->identity_id)->get()->first();
        $timeZoneId     = PermissionTrait::getTimeZoneId();
        if ($mapWebsiteInfo) {
            $regex_map_identity                       = Regex_map_identity::findOrfail($mapWebsiteInfo->regex_id);
            $regex_map_identity->regex_block_level_id = $request['regex_block_level_id'];
            $regex_map_identity->update_timezone      = $timeZoneId;
            $regex_map_identity->update_date          = date('Ymd');
            $regex_map_identity->update_time          = time();
            $regex_map_identity->save();
        } else {
            $regex_map_identity                       = new Regex_map_identity();
            $regex_map_identity->identity_table_id    = 56;
            $regex_map_identity->identity_id          = $request->identity_id;
            $regex_map_identity->regex_block_level_id = $request['regex_block_level_id']['id'];
            $regex_map_identity->update_timezone      = $timeZoneId;
            $regex_map_identity->update_date          = date('Ymd');
            $regex_map_identity->update_time          = time();
            $regex_map_identity->status               = 1;
            $regex_map_identity->save();
        }
        return 1;
    }

    public function deleteWebsite(Request $request)
    {
        $identityId       = $request['identity_id'];
        $identity_website = Identity_website::findOrFail($identityId);
        $identity_website->delete();

        $mapWebsiteInfo = Regex_map_identity::where("identity_id", $identityId)->get()->first();
        if ($mapWebsiteInfo) {
            $regex_map_identity = Regex_map_identity::findOrfail($mapWebsiteInfo->regex_id);
            $regex_map_identity->delete();
        }
        return 1;
    }

    public function getBlockLevel(Request $request)
    {
        $identityId     = $request->identity_id;
        $mapWebsiteInfo = Regex_map_identity::where("identity_id", $identityId)->get()->first();
        if ($mapWebsiteInfo && $mapWebsiteInfo->regex_block_level_id != 0) {
            $blockLevelId       = $mapWebsiteInfo->regex_block_level_id;
            $selectedBlockLevel = Regex_block_level::where('id', $blockLevelId)->get();
            $blockLevel         = Regex_block_level::where('id', '!=', $blockLevelId)
                ->get();
            $resultData[0] = $selectedBlockLevel[0];

            for ($i = 1; $i <= count($blockLevel); $i++) {
                $resultData[$i] = $blockLevel[$i - 1];
            }
            return json_encode($resultData);
        } else {
            $blockLevel = Regex_block_level::all();
            return json_encode($blockLevel);
        }
    }

    public function createBlockLevel(Request $request)
    {
        $blockLevel          = new Regex_block_level();
        $blockLevel->pattern = $request['pattern'];
        $blockLevel->save();
        return 1;
    }

    public function updateBlockLevel(Request $request)
    {
        $blockLevel          = Regex_block_level::findOrfail($request->id);
        $blockLevel->pattern = $request->pattern;
        $blockLevel->save();
        return 1;
    }

    public function deleteBlockLevel(Request $request)
    {
        $blockLevel = Regex_block_level::findOrFail($request->id);
        $blockLevel->delete();

        $mapIdentityInfo = Regex_map_identity::where("regex_block_level_id", $request->id)->get();
        if (count($mapIdentityInfo) > 0) {
            foreach ($mapIdentityInfo as $blockValue) {
                $regex_map_identity                       = Regex_map_identity::findOrfail($blockValue->regex_id);
                $regex_map_identity->regex_block_level_id = 0;
                $regex_map_identity->update_date          = date('Ymd');
                $regex_map_identity->update_time          = time();
                $regex_map_identity->save();
            }
        }
        return 1;
    }

    public function assignWebsiteBlockLevel(Request $request)
    {
        $blockLevel = explode(',', $request->id);
        $identityId = $request->block_identity_id;

        $mapIdentityInfo = Regex_map_identity::where("identity_id", $identityId)->get()->first();
        $timeZoneId      = PermissionTrait::getTimeZoneId();
        if ($mapIdentityInfo) {
            $regex_map_identity                       = Regex_map_identity::findOrfail($mapIdentityInfo->regex_id);
            $regex_map_identity->regex_block_level_id = $request->id;
            $regex_map_identity->update_timezone      = $timeZoneId;
            $regex_map_identity->update_date          = date('Ymd');
            $regex_map_identity->update_time          = time();
            $regex_map_identity->save();
        } else {
            $regex_map_identity                       = new Regex_map_identity();
            $regex_map_identity->identity_table_id    = 56;
            $regex_map_identity->identity_id          = $identityId;
            $regex_map_identity->regex_block_level_id = $request->id;
            $regex_map_identity->update_timezone      = $timeZoneId;
            $regex_map_identity->update_date          = date('Ymd');
            $regex_map_identity->update_time          = time();
            $regex_map_identity->status               = 1;
            $regex_map_identity->save();
        }
        return 1;
    }

    public function getBlockElementList(Request $request)
    {
        $identityId     = $request->identity_id;
        $mapWebsiteInfo = Regex_map_identity::where("identity_id", $identityId)->get()->first();
        if ($mapWebsiteInfo && $mapWebsiteInfo->regex_block_element_id != 0) {
            $blockElementId       = $mapWebsiteInfo->regex_block_element_id;
            $selectedBlockElement = Regex_block_element::where('element_id', $blockElementId)->get();
            $blockElement         = Regex_block_element::where('element_id', '!=', $blockElementId)->get();
            $resultData[0]        = $selectedBlockElement[0];
            for ($i = 1; $i <= count($blockElement); $i++) {
                $resultData[$i] = $blockElement[$i - 1];
            }
            return json_encode($resultData);
        } else {
            $blockElement = Regex_block_element::all();
            return json_encode($blockElement);
        }
    }

    public function createBlockElement(Request $request)
    {
        $blockElement                = new Regex_block_element();
        $blockElement->pattern_start = $request['pattern_start'];
        $blockElement->pattern_end   = $request['pattern_end'];
        $blockElement->save();
        return 1;
    }

    public function updateBlockElement(Request $request)
    {
        $blockElement                = Regex_block_element::findOrfail($request->element_id);
        $blockElement->pattern_start = $request->pattern_start;
        $blockElement->pattern_end   = $request->pattern_end;
        $blockElement->save();
        return 1;
    }

    public function deleteBlockElement(Request $request)
    {
        $blockElement = Regex_block_element::findOrFail($request->element_id);
        $blockElement->delete();

        $mapIdentityInfo = Regex_map_identity::where("regex_block_element_id", $request->element_id)->get();
        if (count($mapIdentityInfo) > 0) {
            foreach ($mapIdentityInfo as $blockValue) {
                $regex_map_identity                         = Regex_map_identity::findOrfail($blockValue->regex_id);
                $regex_map_identity->regex_block_element_id = 0;
                $regex_map_identity->update_date            = date('Ymd');
                $regex_map_identity->update_time            = time();
                $regex_map_identity->save();
            }
        }
        return 1;
    }

    public function assignWebsiteBlockElement(Request $request)
    {
        $blockElement = explode(',', $request->element_id);
        $identityId   = $request->element_identity_id;

        $mapIdentityInfo = Regex_map_identity::where("identity_id", $identityId)->get()->first();
        $timeZoneId      = PermissionTrait::getTimeZoneId();
        if ($mapIdentityInfo) {
            $regex_map_identity                         = Regex_map_identity::findOrfail($mapIdentityInfo->regex_id);
            $regex_map_identity->regex_block_element_id = $request->element_id;
            $regex_map_identity->update_timezone        = $timeZoneId;
            $regex_map_identity->update_date            = date('Ymd');
            $regex_map_identity->update_time            = time();
            $regex_map_identity->save();
        } else {
            $regex_map_identity                         = new Regex_map_identity();
            $regex_map_identity->identity_table_id      = 56;
            $regex_map_identity->identity_id            = $identityId;
            $regex_map_identity->regex_block_element_id = $request->element_id;
            $regex_map_identity->update_timezone        = $timeZoneId;
            $regex_map_identity->update_date            = date('Ymd');
            $regex_map_identity->update_time            = time();
            $regex_map_identity->status                 = 1;
            $regex_map_identity->save();
        }
        return 1;
    }

    public function getPaginationList(Request $request)
    {
        $identityId     = $request->identity_id;
        $mapWebsiteInfo = Regex_map_identity::where("identity_id", $identityId)->get()->first();
        if ($mapWebsiteInfo && $mapWebsiteInfo->pagination_id != 0) {
            $paginationId       = $mapWebsiteInfo->pagination_id;
            $selectedPagination = Regex_pagination::where('pagination_id', $paginationId)->get();
            $paginations        = Regex_pagination::where('pagination_id', '!=', $paginationId)->get();
            $resultData[0]      = $selectedPagination[0];

            for ($i = 1; $i <= count($paginations); $i++) {
                $resultData[$i] = $paginations[$i - 1];
            }
            return json_encode($resultData);
        } else {
            $regexPagination = Regex_pagination::all();
            return json_encode($regexPagination);
        }
    }

    public function createPagination(Request $request)
    {
        $regexPagination                       = new Regex_pagination();
        $regexPagination->pagination_url       = $request['pagination_url'];
        $regexPagination->pagination_format    = $request['pagination_format'];
        $regexPagination->pagination_increment = $request['pagination_increment'];
        $regexPagination->save();
        return 1;
    }

    public function updatePagination(Request $request)
    {
        $regexPagination                       = Regex_pagination::findOrfail($request->pagination_id);
        $regexPagination->pagination_url       = $request['pagination_url'];
        $regexPagination->pagination_format    = $request['pagination_format'];
        $regexPagination->pagination_increment = $request['pagination_increment'];
        $regexPagination->save();
        return 1;
    }

    public function deletePagination(Request $request)
    {
        $regexPagination = Regex_pagination::findOrFail($request->pagination_id);
        $regexPagination->delete();

        $mapIdentityInfo = Regex_map_identity::where("pagination_id", $request->pagination_id)->get();
        if (count($mapIdentityInfo) > 0) {
            foreach ($mapIdentityInfo as $blockValue) {
                $regex_map_identity                = Regex_map_identity::findOrfail($blockValue->regex_id);
                $regex_map_identity->pagination_id = 0;
                $regex_map_identity->update_date   = date('Ymd');
                $regex_map_identity->update_time   = time();
                $regex_map_identity->save();
            }
        }
        return 1;
    }

    public function assignWebsitePagination(Request $request)
    {
        $blockElement = explode(',', $request->element_id);
        $identityId   = $request->pagination_identity_id;

        $mapIdentityInfo = Regex_map_identity::where("identity_id", $identityId)->get()->first();
        $timeZoneId      = PermissionTrait::getTimeZoneId();
        if ($mapIdentityInfo) {
            $regex_map_identity                  = Regex_map_identity::findOrfail($mapIdentityInfo->regex_id);
            $regex_map_identity->pagination_id   = $request->pagination_id;
            $regex_map_identity->update_timezone = $timeZoneId;
            $regex_map_identity->update_date     = date('Ymd');
            $regex_map_identity->update_time     = time();
            $regex_map_identity->save();
        } else {
            $regex_map_identity                    = new Regex_map_identity();
            $regex_map_identity->identity_table_id = 56;
            $regex_map_identity->identity_id       = $identityId;
            $regex_map_identity->pagination_id     = $request->pagination_id;
            $regex_map_identity->update_timezone   = $timeZoneId;
            $regex_map_identity->update_date       = date('Ymd');
            $regex_map_identity->update_time       = time();
            $regex_map_identity->status            = 1;
            $regex_map_identity->save();
        }
        return 1;
    }

    public function relativeToAbsoluteUrl($sourceRelativeUrl, $sourceBaseUrl)
    {
        if (parse_url($sourceRelativeUrl, PHP_URL_SCHEME) != '' || substr($sourceRelativeUrl, 0, 2) == '//') {
            return $sourceRelativeUrl;
        }
        if ($sourceRelativeUrl[0] == '#' || $sourceRelativeUrl[0] == '?') {
            return $sourceBaseUrl . $sourceRelativeUrl;
        }
        $urlPart         = (parse_url($sourceBaseUrl));
        $urlPart['path'] = (isset($urlPart['path'])) ? preg_replace('#/[^/]*$#', '', $urlPart['path']) : null;
        if ($sourceRelativeUrl[0] == '/') {
            $urlPart['path'] = '';
        }
        $absoluteUrl = $urlPart['host'] . $urlPart['path'] . '/' . $sourceRelativeUrl;
        $relativeUrl = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
        return $urlPart['scheme'] . '://' . $absoluteUrl;
    }

    public function scrapeWebsiteLinkData(Request $request)
    {
        $websiteId      = $request->website_id;
        $websiteMapData = Regex_map_identity::select('regex_map_identity.*',
            'identity_website.identity_website',
            'regex_block_level.pattern as block_pattern',
            'regex_block_element.pattern_start',
            'regex_block_element.pattern_end',
            'regex_pattern.pattern as regex_pattern',
            'regex_pagination.*')
            ->join('identity_website', 'identity_website.identity_id', 'regex_map_identity.identity_id')
            ->leftjoin('regex_block_level', 'regex_block_level.id', 'regex_map_identity.regex_block_level_id')
            ->leftjoin('regex_block_element', 'regex_block_element.element_id', 'regex_map_identity.regex_block_element_id')
            ->leftjoin('regex_pattern', 'regex_pattern.pattern_id', 'regex_map_identity.regex_pattern_id')
            ->leftjoin('regex_pagination', 'regex_pagination.pagination_id', 'regex_map_identity.pagination_id')
            ->where('regex_map_identity.identity_id', $websiteId)->get();
        $appPath        = app_path();
        $scrapeFilePath = $appPath . 'logs/' . 'ScrapeContent.txt';

        foreach ($websiteMapData as $key => $urlDetail) {
            $websiteUrl = $urlDetail->identity_website;
            if (strpos($websiteUrl, 'http://') === false && strpos($websiteUrl, 'https://') === false) {
                $websiteUrl = 'http://' . $urlDetail->identity_website;
            } else {
                $websiteUrl = $urlDetail->identity_website;
            }
            $blockElement = $urlDetail->pattern_start;
            if ($urlDetail->block_pattern) {
                $blockPattern = explode("|", $urlDetail->block_pattern);
                $firstBlock   = trim($blockPattern[0]);
                //$secondBlock = trim($blockPattern[1]);

                $eventHtml = HtmlDomParser::file_get_html($websiteUrl);
                if ($eventHtml) {
                    if ($blockElement && count($eventHtml->find($blockElement)) > 0) {
                        foreach ($eventHtml->find($blockElement) as $blockElementData) {
                            $blockHtmlData      = $blockElementData->innertext;
                            $scrapeRecordsCount = count($blockElementData->find($firstBlock));

                            foreach ($blockElementData->find($firstBlock) as $firstBlockData) {
                                $htmlEnd        = $firstBlockData->innertext;
                                $levelId        = $urlDetail->regex_block_level_id;
                                $blockDelimiter = Regex_block_delimiter::where('level_id', $levelId)->get();
                                $blockFieldKey  = Regex_block_delimiter::select('field_key')
                                    ->where('level_id', $levelId)
                                    ->where('field_key', 1)->get();

                                foreach ($blockDelimiter as $key => $delimiterData) {
                                    $delimiterId    = $delimiterData->delimiter_id;
                                    $delimiterStart = $delimiterData->delimiter_start;
                                    $delimiterEnd   = $delimiterData->delimiter_end;
                                    $fieldName      = $delimiterData->field_name;
                                    $fieldKey       = $delimiterData->field_key;

                                    $firstBlocksData = $firstBlockData->find($delimiterStart);
                                    foreach ($firstBlocksData as $key => $fieldBlockData) {
                                        $fieldData = $fieldBlockData->find($delimiterEnd, 0);
                                        if ($fieldName == 'linkedin_url') {
                                            if (isset($fieldData->href) && ($fieldData->href != '' || !empty($fieldData->href))) {
                                                $fieldResult = $this->relativeToAbsoluteUrl($fieldData->href, $websiteUrl);
                                            }
                                        } else {
                                            $fieldResult = trim($fieldData->plaintext);
                                        }

                                        $timeZoneId = PermissionTrait::getTimeZoneId();
                                        if (count($blockFieldKey) == 0) {
                                            $regex_result                  = new Regex_result_delimiter();
                                            $regex_result->delimiter_id    = $delimiterId;
                                            $regex_result->result_value    = $fieldResult;
                                            $regex_result->update_timezone = $timeZoneId;
                                            $regex_result->update_date     = date('Ymd');
                                            $regex_result->update_time     = time();
                                            $regex_result->save();
                                        } else {
                                            $regexResultData = Regex_result_delimiter::select('result_id', 'result_value')->where('delimiter_id', $delimiterId)->get();

                                            $regexResultArray = [];
                                            foreach ($regexResultData as $resultKey => $resultValue) {
                                                $resultId                    = $resultValue['result_id'];
                                                $regexResultArray[$resultId] = $resultValue['result_value'];
                                            }

                                            $regexResultId = array_search($fieldResult, $regexResultArray);
                                            if ($regexResultId) {
                                                $regex_result                  = Regex_result_delimiter::findOrfail($regexResultId);
                                                $regex_result->result_value    = $fieldResult;
                                                $regex_result->update_timezone = $timeZoneId;
                                                $regex_result->update_date     = date('Ymd');
                                                $regex_result->update_time     = time();
                                                $regex_result->save();
                                            } else {
                                                $regex_result                  = new Regex_result_delimiter();
                                                $regex_result->delimiter_id    = $delimiterId;
                                                $regex_result->result_value    = $fieldResult;
                                                $regex_result->update_timezone = $timeZoneId;
                                                $regex_result->update_date     = date('Ymd');
                                                $regex_result->update_time     = time();
                                                $regex_result->save();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return 1;
    }
}
