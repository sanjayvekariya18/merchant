<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Users_language;
use Amranidev\Ajaxis\Ajaxis;
use App\Merchant;
use App\Staff;
use App\Merchant_type;
use App\Location_list;
use App\Customer_group;
use App\Customer;
use URL;
use DB;
use Auth;
use Session;
use App\Http\Traits\PermissionTrait;
use Input;
use DateTime;
use Carbon\Carbon;

/**
 * Class Users_languageController.
 *
 * @author  The scaffold-interface created at 2017-05-12 03:07:58pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Hase_users_languageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->staffId = session()->has('staffId') ? session()->get('staffId') :"";
            $this->identity_table_id = session()->has('identity_table_id') ? session()->get('identity_table_id') :"";
            $this->staffName = session()->has('staffName') ? session()->get('staffName') :"";
            $this->merchantId = session()->has('merchantId') ? session()->get('merchantId') :"";
            $this->roleId = session()->has('role') ? session()->get('role') :"";
            $this->locationId = session()->has('locationId') ? session()->get('locationId') :"";
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Index - hase_users_language';
        $users_languages = /*Users_language::
                            join('users as userName ','users_languages.user_id','=','userName.user_id')
                            ->join('languages','languages.language_id','=','users_languages.language_id')
                            ->select('users_languages.*','languages.*','userName.username as userName')->get();*/
                            $users_languages=DB::table('identity_language_list')
                        ->leftjoin('languages','languages.language_id','=','identity_language_list.language_id')
                        ->where('identity_id','=',$this->staffId)
                        ->where('identity_table_id','=','9')
                        ->get();

        return view('hase_users_language.index',compact('title','users_languages'));
    }
public function languageIdentitites(){
    if($this->staffId == 1){
        $users_languages=DB::table('identity_language_list')
                        ->get(); 
        foreach ($users_languages as $users_languages_value) {
                    $originTable = PermissionTrait::getTableType($users_languages_value->identity_table_id);
                    $originTableInfo = PermissionTrait::getIdentityTableType($originTable->table_code,$users_languages_value->identity_id);
                    $identityTable = PermissionTrait::getTableType($originTableInfo->identity_table_id);
                 $originTableName = $originTable->table_code;
                  $identityTableName = $identityTable->table_code;
                  
                    return $identity_language_list = DB::table('identity_language_list')->select('identity_language_list.*', 
                            'languages.*',
                            $identityTableName.'.identity_name',
                            $identityTableName.'.identity_code','identity_table_type.table_name')
                        ->join('languages','languages.language_id','=','identity_language_list.language_id')
                        ->join($originTableName,$originTableName.'.identity_id','identity_language_list.identity_id')
                        ->join('identity_table_type','identity_table_type.type_id','=','identity_language_list.identity_table_id')
                        ->join($identityTableName,$identityTableName.'.identity_id','identity_language_list.identity_id')->get();
        } 
    }else{
        $users_languages=DB::table('identity_language_list')
                        ->get();
                    foreach ($users_languages as $users_languages_value) {
                    $originTable = PermissionTrait::getTableType($users_languages_value->identity_table_id);
                    $originTableInfo = PermissionTrait::getIdentityTableType($originTable->table_code,$users_languages_value->identity_id);
                    $identityTable = PermissionTrait::getTableType($originTableInfo->identity_table_id);
                    $originTableName = $originTable->table_code;
                    $identityTableName = $identityTable->table_code;
                    return $identity_language_list = DB::table('identity_language_list')->select('identity_language_list.*', 
                            'languages.*',
                            $identityTableName.'.identity_name',
                            $identityTableName.'.identity_code','identity_table_type.table_name')
                        ->join('languages','languages.language_id','=','identity_language_list.language_id')
                        ->join($originTableName,$originTableName.'.identity_id','identity_language_list.identity_id')
                        ->join($identityTableName,$identityTableName.'.identity_id','identity_language_list.identity_id')
                        ->join('identity_table_type','identity_table_type.type_id','=','identity_language_list.identity_table_id')
                        ->where('identity_language_list.identity_id','=',$this->staffId)
                        ->where('identity_language_list.identity_table_id','=',$this->identity_table_id)->get(); 
        }
    }       
}
    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create - hase_users_language';

         $Users_language=DB::table('languages')->get();
        if($this->merchantId == 0){
                $where[] = array(
                    'key' => "merchant.merchant_id",
                    'operator' => '>',
                    'val' => $this->merchantId
                );
        }else{
            if($this->roleId == 4){
                $where[] = array(
                    'key' => "merchant.merchant_id",
                    'operator' => '=',
                    'val' => $this->merchantId
                );
            }else{
                $where[] = array(
                    'key' => "merchant.merchant_id",
                    'operator' => '=',
                    'val' => $this->merchantId
                );
            }
        }

        $merchants = Merchant::
        distinct('merchant.merchant_id')
        ->select(
            DB::raw("CONCAT('merchant_',merchant.merchant_id) AS person_id"),
            'identity_merchant.identity_name as person_name',
            'identity_merchant.identity_code as person_code')
        ->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
        ->join('merchant_account_list','merchant_account_list.merchant_id','=','merchant.merchant_id')
        ->where(function($q) use ($where){
            foreach($where as $key => $value){
                $q->where($value['key'], $value['operator'], $value['val']);
            }
        })
        ->get()->toArray();

        $customers = Customer::distinct('customers.customer_id')
        ->select(
            DB::raw("CONCAT('customers_',customers.customer_id) AS person_id"),
            'identity_customer.identity_name as person_name',
            'identity_customer.identity_code as person_code')
        ->join('identity_customer','identity_customer.identity_id','customers.identity_id')
        ->join('customer_account_list','customer_account_list.customer_id','=','customers.customer_id')
        ->where('customers.customer_id','!=',0)
        ->get()->toArray();

         $staff = Staff::distinct('staff.staff_id')
        ->select(
            DB::raw("CONCAT('staff_',staff.staff_id) AS person_id"),
            'identity_staff.identity_name as person_name',
            'identity_staff.identity_code as person_code')
        ->join('identity_staff','identity_staff.identity_id','staff.identity_id')
        ->where('staff.staff_id','!=',0)
        ->get()->toArray();        
        $people = array_merge($merchants,$customers,$staff);                      
        return view('hase_users_language.language',compact('title','Users_language','hase_merchants','people'));
    }
    public function getIdentities(Request $request)
    {

        switch ($request->identity_table_id) {
            case 4:
                $customers = PermissionTrait::getCustomers();
                return json_encode($customers);
                break;
            case 8:
                $merchants = PermissionTrait::getMerchants();
                return json_encode($merchants);
                break;
            case 35:
                $staff = PermissionTrait::getStaff();
                return json_encode($staff);
                break;
            default:
                return false;
                break;
        }

    }

public function updateDetails(Request $request)
    {
        $identity_table_id=$request->identity_table_id;
        if($identity_table_id == '35'){
             $identity_table='identity_staff';
        }elseif($identity_table_id == '8'){
            $identity_table='identity_merchant';
        }else{
            $identity_table='identity_customer';
        }
        $identity_detail_list=DB::table($identity_table)->where('identity_id','=',$request->identity_id)->first(); 
        $identity_id_list=DB::table('identity_language_list')->where('language_id','=',$request->language_id)->where('identity_id','=',$identity_detail_list->identity_id)->where('identity_table_id','=',$identity_detail_list->identity_table_id)->first();
      if(!isset($identity_id_list)){
        DB::table('identity_language_list')->insert(array('language_id'=>$request->language_id,'identity_id'=>$identity_detail_list->identity_id,'identity_table_id'=>$identity_detail_list->identity_table_id,'priority'=>$request->language_priority));
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
        $users_language = new Users_language();
        $users_language->user_id = $this->roleId;
        $users_language->language_id = $request->language_id;
        $identity_details = $request->person_id;
        $identity_detail = explode('_', $identity_details);
        $identity_detail_list=DB::table($identity_detail[0])->where($identity_detail[0].'_id','=',$identity_detail[1])->first();
        $identity_id_list=DB::table('identity_language_list')->where('language_id','=',$request->language_id)->where('identity_id','=',$identity_detail_list->identity_id)->where('identity_table_id','=',$identity_detail_list->identity_table_id)->first();
      if(!isset($identity_id_list)){
        DB::table('identity_language_list')->insert(array('language_id'=>$request->language_id,'identity_id'=>$identity_detail_list->identity_id,'identity_table_id'=>$identity_detail_list->identity_table_id));
    }
        $pusher = App::make('pusher');
        Session::flash('type', 'success'); 
        Session::flash('msg', 'Language Successfully Inserted');

        //default pusher notification.
        //by default channel=test-channel,event=test-event
        //Here is a pusher notification example when you create a new resource in storage.
        //you can modify anything you want or use it wherever.
        $pusher->trigger('test-channel',
                         'test-event',
                        ['message' => 'A new users_language has been created !!']);

        return redirect('users_language');
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
        $title = 'Show - hase_users_language';

        if($request->ajax())
        {
            return URL::to('users_language/'.$id);
        }

        $users_language = Users_language::findOrfail($id);
        return view('hase_users_language.show',compact('title','users_language'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        $title = 'Edit - hase_users_language';
        if($request->ajax())
        {
            return URL::to('users_language/'. $id . '/edit');
        }
        $users_language = Users_language::
                            join('languages','languages.language_id','=','users_languages.language_id')
                            ->select('users_languages.*','language_name','language_code')
                            ->where('users_languages.id',$id)
                           ->get()->first();
                            
        $Users_language_list=DB::table('languages')->get();
        return view('hase_users_language.edit',compact('title','users_language','Users_language_list'));
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
        $users_language = Users_language::findOrfail($id);
        
        $users_language->id = $request->id;
        
        $users_language->language_id = $request->language_id;
        
        $users_language->user_id = $this->roleId;

        $userLanguagePriority=DB::table('users_languages')->where('user_id','=',$this->roleId)->where('language_priority','=',$request->language_priority)->where('language_id','!=',$request->language_id)->first();
        
        if(isset($userLanguagePriority->language_priority)){
            $users_language->language_priority = 0;
        }else{
            $users_language->language_priority = $request->language_priority;
        }
        
        if(isset($userLanguagePriority->language_priority)){
            $languagePriorityMessage=' But language priority is already set which you insert please edit it.';
        }else{
            $languagePriorityMessage='';
        }
        
        $users_language->save();
        Session::flash('type', 'success'); 
        Session::flash('msg', 'Language Successfully Updated'.$languagePriorityMessage);

        return redirect('users_language');
    }

    /**
     * Delete confirmation message by Ajaxis.
     *
     * @link      https://github.com/amranidev/ajaxis
     * @param    \Illuminate\Http\Request  $request
     * @return  String
     */
    public function DeleteMsg($id,Request $request)
    {
        $msg = Ajaxis::BtDeleting('Warning!!','Would you like to remove This?','/users_language/'. $id . '/delete');

        if($request->ajax())
        {
            return $msg;
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
        $users_language = Users_language::findOrfail($id);
        $users_language->delete();
        return URL::to('users_language');
    }
}
