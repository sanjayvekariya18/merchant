<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Merchant_retail_category_type;
use App\Exchange_category_list;
use App\Exchange;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;

use URL;
use Session;
use DB;
use Redirect;

/**
 * Class Exchange_category_listController.
 *
 * @author  The scaffold-interface created at 2018-02-25 05:27:58pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Exchange_category_listController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Exchange_category_list');

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
        if($this->permissionDetails('Exchange_category_list','access')){
                       
            $permissions = $this->getPermission("Exchange_category_list");
            
            if($this->merchantId == 0){

                $exchange_category_lists = Exchange_category_list::
                    select('identity_exchange.identity_code as exchange_symbol','identity_exchange.identity_name as exchange_name','identity_category_type.identity_name as category_type_name','exchange_category_list.*')
                    ->join('exchange','exchange.exchange_id','=','exchange_category_list.exchange_id')
                    ->join('identity_exchange','identity_exchange.identity_id','=','exchange.identity_id')
                    ->join('merchant_retail_category_type','merchant_retail_category_type.category_type_id','=','exchange_category_list.category_type_id')
                    ->join('identity as identity_category_type','identity_category_type.identity_id','=','merchant_retail_category_type.identity_id')
                    ->get();

                return view('exchange_category_list.index',compact('exchange_category_lists','permissions'));
            }
        }else{
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

        if($this->permissionDetails('Exchange_category_list','add')){

            $exchanges = Exchange::select('identity_exchange.identity_name as exchange_name','exchange.exchange_id')
                                ->join('identity_exchange','identity_exchange.identity_id','=','exchange.identity_id')
                                ->get();

            $category_types = Merchant_retail_category_type::
                    select('category_type_id','category_type_identity.identity_name as category_name')
                    ->join('identity as category_type_identity','category_type_identity.identity_id','=','merchant_retail_category_type.identity_id')
                    ->get();   

            return view('exchange_category_list.create',compact('exchanges','category_types'));
        }else{
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
        if(isset($request->category_type_id) && count($request->category_type_id) != 0){

            Exchange_category_list::
                where('exchange_id',$request->exchange_id)
                ->whereNotIn('category_type_id',$request->category_type_id)
                ->delete();


            foreach ($request->category_type as $value) {

                $category_exist = Exchange_category_list::
                          where('exchange_id',$request->exchange_id)
                        ->where('category_type_id',$value['category_type_id'])
                        ->get()->first();

                if(count($category_exist) == 0){
                    $exchange_category_list = new Exchange_category_list();
                }else{
                    $exchange_category_list = Exchange_category_list::findOrfail($category_exist->list_id);
                }

                $exchange_category_list->exchange_id = $request->exchange_id;
                $exchange_category_list->category_type_id = $value['category_type_id'];
                $exchange_category_list->priority = $value['priority'];
                $exchange_category_list->status = isset($value['status'])?1:0;
                
                $exchange_category_list->save();

            }
        }else{
            Exchange_category_list::
                where('exchange_id',$request->exchange_id)
                ->delete();
        }

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Exchange Category List Successfully Created');
        if ($request->submitBtn === "Save") {
           return redirect('exchange_category_list/'. $exchange_category_list->list_id . '/edit');
        }else{
           return redirect('exchange_category_list');
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
     	
        if($this->permissionDetails('Exchange_category_list','delete')){
            $exchange_category_list = Exchange_category_list::findOrfail($id);
            $exchange_category_list->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Exchange Category List Successfully Deleted');
            return redirect('exchange_category_list');
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getCategoryTypes(Request $request) {
        
        $categoryTypes = Exchange_category_list::
                        distinct('exchange_category_list.exchange_id','exchange_category_list.category_type_id')
                        ->select('exchange_category_list.*','identity_category_type.identity_name as category_type_name')
                        ->join('merchant_retail_category_type','merchant_retail_category_type.category_type_id','=','exchange_category_list.category_type_id')
                        ->join('identity as identity_category_type','identity_category_type.identity_id','=','merchant_retail_category_type.identity_id')
                        ->where('exchange_category_list.exchange_id',$request->exchange_id)
                        ->get();
                        
        echo json_encode($categoryTypes);
    }
}
