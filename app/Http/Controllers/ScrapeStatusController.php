<?php
namespace App\Http\Controllers;

use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Identity_website;
use App\Scrape_status_history;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Session;
use Redirect;
use URL;

class ScrapeStatusController extends PermissionsController
{
    public function __construct()
    {
        parent::__construct();
        $connectionStatus = ConnectionManager::setDbConfig('ScrapeStatus');
        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
    }

    public function index()
    {
        return view('scrape_status.index');
    }

    public function getScrapeStatusHistory(Request $request)
    {
        $scrapeStatusHistory = Scrape_status_history::select('scrape_status_history.*',
            'identity_table_type.table_code as identity_table',
            'portal_password.username as owner_name')
            ->join('identity_table_type', 'identity_table_type.type_id', 'scrape_status_history.identity_table_id')
            ->join('portal_password','portal_password.user_id','scrape_status_history.owner')
            ->offset($request->skip)
            ->limit($request->take);

        $result['total']         = Scrape_status_history::count();
        $result['scrape_status'] = $scrapeStatusHistory->get();
        foreach ($result['scrape_status'] as $key => $resultData) {
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
            $result['scrape_status'][$key]->scrape_url  = url('htmldom') . '?url=' . $identityWebsite . '&identity_id=' . $resultData->identity_id . '&table_id=' . $resultData->identity_table_id . '&scraped=1';
            $result['scrape_status'][$key]->website_url = $identityWebsite;
        }
        return json_encode($result);
    }
}
