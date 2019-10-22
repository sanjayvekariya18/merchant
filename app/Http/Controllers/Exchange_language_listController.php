<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Exchange_language_list;
use App\Exchange;
use App\Languages;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;
use Session;
use DB;
use Redirect;
use URL;

/**
 * Class Exchange_language_listController.
 *
 */
class Exchange_language_listController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Exchange_language_list');

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
        if($this->permissionDetails('Exchange_language_list','access')) {
            $permissions = $this->getPermission("Exchange_language_list");
            $exchange_language_lists = Exchange_language_list::distinct()->select('exchange_language_list.*',
                'identity_exchange.identity_name as exchange_name',
                'languages.language_name'
                )
                ->join('exchange','exchange.exchange_id','=','exchange_language_list.exchange_id')
                ->join('identity_exchange','exchange.identity_id','=','identity_exchange.identity_id')
                ->leftjoin('languages','languages.language_id','exchange_language_list.language_id')->get();
            return view('exchange_language_list.index',compact('exchange_language_lists','permissions'));
        }
        else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        if($this->permissionDetails('Exchange_language_list','add')) {
            $exchanges = Exchange::select('identity_exchange.identity_code as exchange_code','identity_exchange.identity_name as exchange_name','identity_exchange.identity_website as exchange_website','exchange.*')
                ->join('identity_exchange','identity_exchange.identity_id','=','exchange.identity_id')
                ->get();
            $languages = Languages::All();
            return view('exchange_language_list.create',compact('exchanges','languages'));
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

        if(isset($request->language_id) && count($request->language_id) != 0){

            Exchange_language_list::
                where('exchange_id',$request->exchange_id)
                ->whereNotIn('language_id',$request->language_id)
                ->delete();


            foreach ($request->languages as $value) {

                $language_exist = Exchange_language_list::
                          where('exchange_id',$request->exchange_id)
                        ->where('language_id',$value['language_id'])
                        ->get()->first();

                if(count($language_exist) == 0){
                    $exchange_language_list = new Exchange_language_list();
                }else{
                    $exchange_language_list = Exchange_language_list::findOrfail($language_exist->list_id);
                }

                $exchange_language_list->exchange_id = $request->exchange_id;
                $exchange_language_list->language_id = $value['language_id'];
                $exchange_language_list->priority = $value['priority'];
                $exchange_language_list->status = isset($value['status'])?1:0;
                
                $exchange_language_list->save();

            }
        }else{
            Exchange_language_list::
                where('exchange_id',$request->exchange_id)
                ->delete();
        }

        Session::flash('type', 'success');
        Session::flash('msg', 'Exchange Language List Successfully Inserted');

        if ($request->submitBtn == "Save") {
           return redirect('exchange_language_list/'. $exchange_language_list->list_id . '/edit');
        } else {
           return redirect('exchange_asset_list');
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
        if($this->permissionDetails('Exchange_language_list','delete')) {
         	$exchange_language_list = Exchange_language_list::findOrfail($id);
         	$exchange_language_list->delete();
            Session::flash('type', 'success');
            Session::flash('msg', 'Exchange Language List Successfully Deleted');
            return redirect('exchange_asset_list');
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getLanguages(Request $request) {
        
        $languages = Exchange_language_list::
                        select('exchange_language_list.*','languages.language_name')
                        ->join('languages','languages.language_id','exchange_language_list.language_id')
                        ->where('exchange_id',$request->exchange_id)
                        ->get();
                        
        echo json_encode($languages);
    }
}
