<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Asset_team_list;
use App\Asset;
use App\Asset_team;
use App\People;
use App\Social_apikeys;
use App\Connector;
use App\People_social_list;
use App\Social;
use App\Asset_social_list;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Controllers\ProductApprovalController;
use App\Http\Traits\PermissionTrait;
use Session;
use DB;
use Redirect;
use URL;

/**
 * Class Asset_team_listController.
 *
 */
class Asset_team_listController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Asset_team_list');

        if ($connectionStatus['type'] === "error") {

            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        if($this->permissionDetails('Asset_team_list','access')) {
            $searchTeamList = '';
            $permissions = $this->getPermission("Asset_team_list");
            $asset_team_lists = Asset_team_list::distinct()->select('asset_team_list.*', 'identity_asset.identity_name as asset_name', 'asset_team.team_name','identity_people.identity_name as member_name')
                ->join('asset','asset.asset_id','asset_team_list.asset_id')
                ->join('identity_asset','identity_asset.identity_id','asset.identity_id')
                ->join('asset_team','asset_team.team_id','asset_team_list.team_id')
                ->join('people','people.people_id','=','asset_team_list.member_id')
                ->join('identity_people','identity_people.identity_id','=','people.identity_id')
                ->paginate(25);
            
            return view('asset_team_list.index',compact('asset_team_lists','permissions','searchTeamList'));
        }
        else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
    /**
     * search resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $searchTeamList =  trim($request->search_team_list);
        if($searchTeamList != "") {
            $permissions = $this->getPermission("Asset_team_list");
            $asset_team_lists = Asset_team_list::distinct()->select('asset_team_list.*', 'identity_asset.identity_name as asset_name', 'asset_team.team_name','identity_people.identity_name as member_name')
                ->join('asset','asset.asset_id','asset_team_list.asset_id')
                ->join('identity_asset','identity_asset.identity_id','asset.identity_id')
                ->join('asset_team','asset_team.team_id','asset_team_list.team_id')
                ->join('people','people.people_id','=','asset_team_list.member_id')
                ->join('identity_people','identity_people.identity_id','=','people.identity_id')
                ->where('identity_asset.identity_name', 'LIKE', '%' . $searchTeamList . '%' )
                ->orwhere('asset_team.team_name', 'LIKE', '%' . $searchTeamList . '%' )
                ->orwhere('identity_people.identity_name', 'LIKE', '%' . $searchTeamList . '%' )
                ->paginate(25)->setPath('');

            $pagination = $asset_team_lists->appends(array(
                'search_team_list' => $searchTeamList
            ));
            if (count($asset_team_lists) > 0) 
            {
                return view('asset_team_list.index',compact('asset_team_lists','permissions','searchTeamList'))->withDetails($asset_team_lists)->withQuery($searchTeamList);
            }
            return view('asset_team_list.index', compact('asset_team_lists','permissions','searchTeamList'))->withMessage('No Details found. Try to search again !');
        } else {
            return redirect('asset_team_list');
        }

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        if($this->permissionDetails('Asset_team_list','add')) {
            $assets = Asset::select('identity_asset.identity_name as asset_name','asset.asset_id',
                'identity_asset.identity_code as asset_code')
                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                ->where('identity_asset.identity_id','!=',0)
                ->get();

            $peoples = People::select('people.people_id','identity_people.identity_name as people_name')
                ->join('identity_people','identity_people.identity_id','=','people.identity_id')
                ->get();    
            
            $assetTeams = Asset_team::all();
            $socials = Social::select('social_id',
                'identity_social.identity_code as social_code','identity_social.identity_name as social_name')
                ->join('identity_social','identity_social.identity_id','=','social.identity_id')
                ->get();  

            return view('asset_team_list.create',compact('assets','assetTeams','peoples','socials'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $member_social_list = array();

        if(isset($request->member_id) && count($request->member_id) != 0){

            Asset_team_list::
                where('asset_id',$request->asset_id)
                ->where('team_id',$request->team_id)
                ->whereNotIn('member_id',$request->member_id)
                ->delete();


            foreach ($request->members as $value) {

                $member_exist = Asset_team_list::
                        where('asset_id',$request->asset_id)
                        ->where('team_id',$request->team_id)
                        ->where('member_id',$value['member_id'])
                        ->get()->first();

                if(count($member_exist) == 0){
                    $asset_team_list = new Asset_team_list();
                }else{
                    $asset_team_list = Asset_team_list::findOrfail($member_exist->list_id);
                }

                
                $asset_team_list->asset_id = $request->asset_id;
                $asset_team_list->team_id = $request->team_id;
                $asset_team_list->member_id = $value['member_id'];
                $asset_team_list->member_title = $value['member_title'];
                $asset_team_list->priority = $value['priority'];
                $asset_team_list->status = isset($value['status'])?1:0;

                if(isset($value['status_date_begin'])) {
                    $beginDate = str_replace('-', '', $value['status_date_begin']);
                    $asset_team_list->status_date_begin = $beginDate;
                } else {
                     $asset_team_list->status_date_begin = 0;
                }

                if(isset($value['status_date_end'])) {
                    $endDate = str_replace('-', '', $value['status_date_end']);
                    $asset_team_list->status_date_end = $endDate;
                } else {
                     $asset_team_list->status_date_end = 0;
                }
                                
                $asset_team_list->save();

            }


            if(isset($request->socials) && count($request->socials) !=0){

                foreach ($request->socials as $member_id => $member_list) {
                    
                    foreach ($member_list as $social_list) {
                        
                        $member_social_list[$member_id][] = $social_list['social_id'];

                        // check people social list record exist or not.

                        $people_social_exist = People_social_list::
                                                    where('people_id',$member_id)
                                                    ->where('social_id',$social_list['social_id'])
                                                    ->get()->first();

                        if(count($people_social_exist) == 0){
                            $people_social_list = new People_social_list();
                        }else{
                            $people_social_list = People_social_list::findOrfail($people_social_exist->list_id);
                        }

                        $people_social_list->people_id = $member_id;
                        $people_social_list->social_id = $social_list['social_id'];
                        $people_social_list->social_url = $social_list['social_url'];
                        $people_social_list->priority = $social_list['priority'];
                        $people_social_list->status = isset($social_list['status'])?1:0;

                        $people_social_list->save();                       
                    }

                    // Delete non listed record in people sopcial list

                        People_social_list::
                            where('people_id',$member_id)
                            ->whereNotIn('social_id',$member_social_list[$member_id])
                            ->delete();
                }

            }


        }else{
            Asset_team_list::
                where('asset_id',$request->asset_id)
                ->where('team_id',$request->team_id)
                ->delete();
        }        

        Session::flash('type', 'success');
        Session::flash('msg', 'Asset Team List Successfully Inserted');

        if ($request->submitBtn === "Save") {
           return redirect('asset_team_list/'. $asset_team_list->list_id . '/edit');
        } else {
           return redirect('asset_team_list');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
        $title = 'Show - asset_team_list';

        if($request->ajax())
        {
            return URL::to('asset_team_list/'.$id);
        }

        $asset_team_list = Asset_team_list::findOrfail($id);
        return view('asset_team_list.show',compact('title','asset_team_list'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        if($this->permissionDetails('Asset_team_list','manage')) {
            $asset_team_list = Asset_team_list::findOrfail($id);
            $assets = Asset::select('identity_asset.identity_name as asset_name','asset.asset_id',
                'identity_asset.identity_code as asset_code')
                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                ->where('identity_asset.identity_id','!=',0)
                ->get();
            $assetTeams = Asset_team::all();
            return view('asset_team_list.edit',compact('assets','asset_team_list','assetTeams'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function update($id,Request $request)
    {
        $asset_team_list = Asset_team_list::findOrfail($id);
    	
        $asset_team_list->asset_id = $request->asset_id;
        $asset_team_list->team_id = $request->team_id;
        $asset_team_list->member_id = $request->member_id;
        $asset_team_list->priority = $request->priority;
        $asset_team_list->save();

        Session::flash('type', 'success');
        Session::flash('msg', 'Asset Team List Successfully Updated');

        if ($request->submitBtn === "Save") {
           return redirect('asset_team_list/'. $asset_team_list->list_id . '/edit');
        } else {
           return redirect('asset_team_list');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    int $id
     * @return  \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->permissionDetails('Asset_team_list','delete')) {
         	$asset_team_list = Asset_team_list::findOrfail($id);
         	$asset_team_list->delete();
            Session::flash('type', 'success');
            Session::flash('msg', 'Asset Team List Successfully Deleted');
            return redirect('asset_team_list');
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getMembers(Request $request) {
        
        $members = Asset_team_list::
                        distinct('asset_team_list.asset_id','asset_team_list.team_id','asset_team_list.member_id')
                        ->select('asset_team_list.member_id','identity_people.identity_name as member_name','asset_team_list.*')
                        ->join('people','people.people_id','=','asset_team_list.member_id')
                        ->join('identity_people','identity_people.identity_id','=','people.identity_id')
                        ->where('asset_team_list.asset_id',$request->asset_id)
                        ->where('asset_team_list.team_id',$request->team_id)
                        ->get();

        echo json_encode($members);
    }

    public function getMemberSocials(Request $request) {
        
        $socials = People_social_list::
                        where('people_id',$request->people_id)
                        ->get();

        echo json_encode($socials);
    }

    public function assets(){
        return view('asset_team_list.list_view');
    }

    public function asset_list(Request $request){

        $social_font = array(
            '1' => 'fab fa-linkedin-in',
            '2' => 'fab fa-btc',
            '3' => 'far fa-file-pdf',
            '4' => 'fas fa-road',
            '5' => 'fab fa-blogger',
            '6' => 'fab fa-btc',
            '7' => 'fab fa-audible',
            '8' => 'fab fa-telegram',
            '9' => 'fab fa-reddit',
            '10' => 'fab fa-twitter',
            '11' => 'fab fa-github',
            '12' => 'fab fa-facebook',
            '13' => 'fab fa-pinterest',
            '14' => 'fab fa-vk',
            '15' => 'fab fa-instagram',
            '16' => 'fab fa-google-plus',
            '17' => 'fab fa-medium',
            '18' => 'fab fa-tumblr',
            '19' => 'fab fa-youtube',
            '20' => 'fab fa-slack',
            '21' => 'fab fa-discord',
            '22' => 'fas fa-globe',
            '23' => 'fas fa-external-link-square-alt'
        );

        
        $asset_lists_query = Asset::select('asset.asset_id','identity_asset.identity_code as asset_code','identity_asset.identity_name as asset_name','identity_asset.identity_website as asset_website')
                    ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                    ->where('asset_id','!=',0);

        if (isset($request->filter)) {
            $searchFiler = $request->filter['filters'][0]['value'];
            $asset_lists_query->where('identity_asset.identity_code', 'LIKE', '%' . $searchFiler . '%');
            $asset_lists_query->orWhere('identity_asset.identity_name', 'LIKE', '%' . $searchFiler . '%');
        }            
         
        $asset_lists = $asset_lists_query->offset($request->skip)->limit($request->take)
                    ->orderBy('asset.asset_id','ASC')->get();
                    
        foreach ($asset_lists as $key=>$asset_list){

                /*CODE GET SOCIAL LIST FOR THE PARTICULAR ASSET AND SET SOCIALS OBJECT*/

                $asset_social_lists = Asset_social_list::select('asset_social_list.social_id','social_url','identity_social.identity_name as social_name')
                                ->join('social','social.social_id','=','asset_social_list.social_id')
                                ->join('identity_social','identity_social.identity_id','=','social.identity_id')
                                ->where('asset_social_list.asset_id',$asset_list->asset_id)
                                ->get();

                $social_urls = array();

                foreach ($asset_social_lists as $asset_social) {

                    if(preg_match('(http|https|www)', $asset_social->social_url) === 0){

                        $asset_social->social_url = "http://".$asset_social->social_url;

                    }

                    $social_link="<a style='font-size: 15px;margin: 5px;' target='_blank' href='".$asset_social->social_url."' title='".$asset_social->social_name."'><i class='".$social_font[$asset_social->social_id]."'></i></a>";
                    $social_urls[]=$social_link;

                }        

                $asset_lists[$key]->socials=$social_urls;

                /*CODE FOR GET TOTAL PEOPLE ASSIGN IN ASSET FOR MAKE ROW AS EXPANDABLE*/

                $asset_team_members = Asset_team_list::
                                        where('asset_id',$asset_list->asset_id)
                                        ->count('asset_id');

                $asset_lists[$key]->peoples=$asset_team_members;   
        }
        $asset_lists_list['asset_list'] = $asset_lists;
        $asset_lists_list['total'] = $asset_lists_query->count('asset.asset_id');
        return json_encode($asset_lists_list);
    }

    public function asset_people_list($assetId){

        $social_font = array(
            '1' => 'fab fa-linkedin-in',
            '2' => 'fab fa-btc',
            '3' => 'far fa-file-pdf',
            '4' => 'fas fa-road',
            '5' => 'fab fa-blogger',
            '6' => 'fab fa-btc',
            '7' => 'fab fa-audible',
            '8' => 'fab fa-telegram',
            '9' => 'fab fa-reddit',
            '10' => 'fab fa-twitter',
            '11' => 'fab fa-github',
            '12' => 'fab fa-facebook',
            '13' => 'fab fa-pinterest',
            '14' => 'fab fa-vk',
            '15' => 'fab fa-instagram',
            '16' => 'fab fa-google-plus',
            '17' => 'fab fa-medium',
            '18' => 'fab fa-tumblr',
            '19' => 'fab fa-youtube',
            '20' => 'fab fa-slack',
            '21' => 'fab fa-discord',
            '22' => 'fas fa-globe',
            '23' => 'fas fa-external-link-square-alt'
        );

        $peoples = Asset_team_list::
                        distinct('asset_team_list.asset_id','asset_team_list.team_id','asset_team_list.member_id')
                        ->select('people.people_id','identity_people.identity_name as people_name','asset_team_list.member_title as people_title')
                        ->join('people','people.people_id','=','asset_team_list.member_id')
                        ->join('identity_people','identity_people.identity_id','=','people.identity_id')
                        ->where('asset_team_list.asset_id',$assetId)
                        ->get();

        foreach ($peoples as $key=>$people){

                $people_social_lists = People_social_list::select('people_social_list.social_id','social_url','identity_social.identity_name as social_name')
                                ->where('people_id',$people->people_id)
                                ->join('social','social.social_id','=','people_social_list.social_id')
                                ->join('identity_social','identity_social.identity_id','=','social.identity_id')
                                ->get();

                $social_urls = array();

                foreach ($people_social_lists as $people_social) {

                    if(preg_match('(http|https|www)', $people_social->social_url) === 0){

                        $people_social->social_url = "http://".$people_social->social_url;

                    }
                    
                    $social_link="<a style='font-size: 15px;margin: 5px;' target='_blank' href='".$people_social->social_url."' title='".$people_social->social_name."'><i class='".$social_font[$people_social->social_id]."'></i></a>";
                    $social_urls[]=$social_link;

                }                        

                $peoples[$key]->socials=$social_urls;
        }                  

        return json_encode($peoples);

        
    }
}
