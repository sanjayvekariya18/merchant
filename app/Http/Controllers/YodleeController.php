<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Event;
use App\Yodlee_login;
use App\Yodlee_cobrand_login;
use App\Yodlee_user_login;
use App\Yodlee_user_app_auth;
use App\Asset_sale_list;
use App\Asset;
use App\Yodlee_account;
use App\Yodlee_account_transaction;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;

use URL;
use Session;
use DB;
use Redirect;

/**
 * Class Asset_sale_listController.
 *
 * @author  The scaffold-interface created at 2018-02-25 05:27:58pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class YodleeController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Yodlee');

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
        if($this->permissionDetails('Yodlee','access')){
                       
            $permissions = $this->getPermission("Yodlee");
            $yodlee_login = Yodlee_login::select('yodlee_login.*','yodlee_cobrand_login.cob_session','yodlee_cobrand_login.application_id','yodlee_user_login.user_session','yodlee_user_login.user_id','yodlee_user_app_auth.access_token','yodlee_user_app_auth.launch_url','yodlee_user_app_auth.app_id')
                ->join('yodlee_cobrand_login','yodlee_cobrand_login.login_id','yodlee_login.cobrand_login_id')
                ->join('yodlee_user_login','yodlee_user_login.login_id','yodlee_login.user_login_id')
                ->join('yodlee_user_app_auth','yodlee_user_app_auth.user_login_id','yodlee_user_login.login_id')
                ->where('yodlee_login.staff_id',$this->staffId)
                ->get()->first();
            $compareTime = time() - 1800;
            if($yodlee_login)
            {
                if($compareTime > $yodlee_login->entry_time)
                {
                    $tokenExpire = true;
                } else {
                    $tokenExpire = false;
                }
            }
            return view('yodlee.index',compact('permissions','yodlee_login','tokenExpire'));
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function saveYadleeSession(Request $request){
        $yodlee_login = Yodlee_login::where('staff_id',$this->staffId)->get()->first();
        if($yodlee_login)
        {
            $yodlee_login = Yodlee_login::firstOrCreate(['staff_id' => $this->staffId]);    
            $yodlee_cobrand_login = Yodlee_cobrand_login::firstOrCreate(['login_id' => $yodlee_login->cobrand_login_id]);
            $yodlee_user_login = Yodlee_user_login::firstOrCreate(['login_id' => $yodlee_login->user_login_id]);
            $yodlee_user_app_auth = Yodlee_user_app_auth::firstOrCreate(['user_login_id' => $yodlee_login->user_login_id]);
        } else {
            $yodlee_login = new Yodlee_login();    
            $yodlee_cobrand_login = new Yodlee_cobrand_login();
            $yodlee_user_login = new Yodlee_user_login();
            $yodlee_user_app_auth = new Yodlee_user_app_auth();
        }
        
        $yodlee_cobrand_login->cobrand_id = $request->co_brand_id;
        $yodlee_cobrand_login->application_id = $request->co_brand_applicationId;
        $yodlee_cobrand_login->cob_session = $request->co_brand_session;
        $yodlee_cobrand_login->locale = $request->co_brand_locale;
        $yodlee_cobrand_login->save();

        $yodlee_user_login->user_id = $request->user_id;
        $yodlee_user_login->user_session = $request->user_session_value;
        $yodlee_user_login->login_name = $request->login_name;
        $yodlee_user_login->first_name = $request->first_name;
        $yodlee_user_login->last_name = $request->last_name;
        $yodlee_user_login->currency = $request->currency;
        $yodlee_user_login->locale = $request->user_locale;
        $yodlee_user_login->roleType = $request->role_type;
        $yodlee_user_login->save();

        $yodlee_user_app_auth->user_login_id = $yodlee_user_login->login_id;
        $yodlee_user_app_auth->app_id = $request->user_app_id;
        $yodlee_user_app_auth->launch_url = $request->application_launch_url;
        $yodlee_user_app_auth->access_token = $request->app_access_token;
        $yodlee_user_app_auth->save();

        
        $yodlee_login->staff_id = $this->staffId;
        $yodlee_login->cobrand_login_id = $yodlee_cobrand_login->login_id;
        $yodlee_login->user_login_id = $yodlee_user_login->login_id;
        $yodlee_login->entry_date = date('Ymd');
        $yodlee_login->entry_time = time();
        $yodlee_login->save();
        
        Session::flash('type', 'success');
        Session::flash('msg', 'Yodlee Login Successfully Done');
        return 1;
        /*return redirect('yodlee');*/
    }
    public function fastLinkLoginForm(){
        $yadleeAccessDetails = Yodlee_login::select('yodlee_login.*','yodlee_cobrand_login.cob_session','yodlee_cobrand_login.application_id','yodlee_user_login.user_session','yodlee_user_login.user_id','yodlee_user_app_auth.access_token','yodlee_user_app_auth.launch_url','yodlee_user_app_auth.app_id')
                ->join('yodlee_cobrand_login','yodlee_cobrand_login.login_id','yodlee_login.cobrand_login_id')
                ->join('yodlee_user_login','yodlee_user_login.login_id','yodlee_login.user_login_id')
                ->join('yodlee_user_app_auth','yodlee_user_app_auth.user_login_id','yodlee_user_login.login_id')
                ->get()->first();
        return view('yodlee.fastlink',compact('yadleeAccessDetails'));
    }

    public function saveYodleeAccount(Request $accountDetails) {
        $yodlee_login = Yodlee_login::select('yodlee_login.yodlee_login_id')
                ->join('yodlee_cobrand_login','yodlee_cobrand_login.login_id','yodlee_login.cobrand_login_id')
                ->join('yodlee_user_login','yodlee_user_login.login_id','yodlee_login.user_login_id')
                ->join('yodlee_user_app_auth','yodlee_user_app_auth.user_login_id','yodlee_user_login.login_id')
                ->where('yodlee_login.staff_id',$this->staffId)
                ->get()->first();
        foreach ($accountDetails->insertAccounts as $accountDetailsKey => $accountDetailsValue) {
            $yodlee_account = Yodlee_account::firstOrCreate(['yodlee_login_id'=>$yodlee_login->yodlee_login_id,'id' => $accountDetailsValue['id']]);
            $yodlee_account->yodlee_login_id = $yodlee_login->yodlee_login_id;
            $yodlee_account->includeIn_net_worth = ($accountDetailsValue['includeInNetWorth'] == 'true')?1:0;
            $yodlee_account->account_name = $accountDetailsValue['accountName'];
            $yodlee_account->is_manual = ($accountDetailsValue['isManual'] == 'true')?1:0;
            $yodlee_account->current_balance_amount = $accountDetailsValue['currentBalance']['amount'];
            $yodlee_account->current_balance_currency = $accountDetailsValue['currentBalance']['currency'];
            $yodlee_account->account_type = $accountDetailsValue['accountType'];
            $yodlee_account->displayed_name = $accountDetailsValue['displayedName'];
            $yodlee_account->account_number = $accountDetailsValue['accountNumber'];
            $yodlee_account->available_balance_amount = $accountDetailsValue['availableBalance']['amount'];
            $yodlee_account->available_balance_currency = $accountDetailsValue['availableBalance']['currency'];
            $yodlee_account->account_status = $accountDetailsValue['accountStatus'];
            $yodlee_account->last_updated = $accountDetailsValue['lastUpdated'];
            $yodlee_account->is_asset = ($accountDetailsValue['isAsset'] == 'true')?1:0;
            $yodlee_account->created_date = $accountDetailsValue['createdDate'];
            $yodlee_account->aggregation_source = $accountDetailsValue['aggregationSource'];
            $yodlee_account->balance_amount = $accountDetailsValue['balance']['amount'];
            $yodlee_account->balance_currency = $accountDetailsValue['balance']['currency'];
            $yodlee_account->provider_id = $accountDetailsValue['providerId'];
            $yodlee_account->provider_account_id = $accountDetailsValue['providerAccountId'];
            $yodlee_account->CONTAINER = $accountDetailsValue['CONTAINER'];
            $yodlee_account->id = $accountDetailsValue['id'];
            $yodlee_account->provider_name = $accountDetailsValue['providerName'];
            $yodlee_account->save();
        }
        
    }
    public function getYodleeAccount(Request $accountDetails) {
        $yodlee_account = Yodlee_account::select('yodlee_account.*','staff.staff_fname','yodlee_login.staff_id',DB::raw('count(yodlee_account_transaction.transaction_id) as totalTransaction'))
            ->join('yodlee_login','yodlee_login.yodlee_login_id','yodlee_account.yodlee_login_id')
            ->join('staff','yodlee_login.staff_id','staff.staff_id')
            ->leftjoin('yodlee_account_transaction','yodlee_account_transaction.account_id','yodlee_account.id')
            ->where('yodlee_login.staff_id',$this->staffId)
            ->groupBy('yodlee_account.account_id')
            ->offset($accountDetails->skip)
            ->limit($accountDetails->take)
            ->get();

        $total_records = Yodlee_account::select('yodlee_account.*','staff.staff_fname','yodlee_login.staff_id',DB::raw('count(yodlee_account_transaction.transaction_id) as totalTransaction'))
            ->join('yodlee_login','yodlee_login.yodlee_login_id','yodlee_account.yodlee_login_id')
            ->join('staff','yodlee_login.staff_id','staff.staff_id')
            ->leftjoin('yodlee_account_transaction','yodlee_account_transaction.account_id','yodlee_account.id')
            ->where('yodlee_login.staff_id',$this->staffId)
            ->groupBy('yodlee_account.account_id')
            ->count();

        $yodlee_account_data['yodlee_account']=$yodlee_account;
        $yodlee_account_data['total']=$total_records;    

        return json_encode($yodlee_account_data);

    }

    public function getUserYodleeAccount(Request $accountDetails) {
        $yodlee_account = Yodlee_account::select('yodlee_account.*','staff.staff_fname','yodlee_login.staff_id',DB::raw('count(yodlee_account_transaction.transaction_id) as totalTransaction'))
            ->join('yodlee_login','yodlee_login.yodlee_login_id','yodlee_account.yodlee_login_id')
            ->join('staff','yodlee_login.staff_id','staff.staff_id')
            ->leftjoin('yodlee_account_transaction','yodlee_account_transaction.account_id','yodlee_account.id')
            ->where('yodlee_login.staff_id',$this->staffId)
            ->groupBy('yodlee_account.account_id')
            ->get();  

        return json_encode($yodlee_account);

    }

    public function saveYodleeTransactions(Request $accountTransaction) {
        foreach ($accountTransaction->insertTransactions as $accountTransactionKey => $accountTransactionValue)
        {
            $yodlee_account_transaction_details = Yodlee_account_transaction::where('account_id',$accountTransactionValue['accountId'])
                ->where('id',$accountTransactionValue['id'])->first();
            if(!$yodlee_account_transaction_details)
            {
                $yodlee_account_transaction = new Yodlee_account_transaction();
                $yodlee_account_transaction->account_id = $accountTransactionValue['accountId'];
                $yodlee_account_transaction->id = $accountTransactionValue['id'];
                $yodlee_account_transaction->container = $accountTransactionValue['CONTAINER'];
                $yodlee_account_transaction->transaction_amount = $accountTransactionValue['amount']['amount'];
                $yodlee_account_transaction->transaction_currency = $accountTransactionValue['amount']['currency'];
                $yodlee_account_transaction->base_type = $accountTransactionValue['baseType'];
                $yodlee_account_transaction->category_type = $accountTransactionValue['categoryType'];
                $yodlee_account_transaction->category_id = $accountTransactionValue['categoryId'];
                $yodlee_account_transaction->category = $accountTransactionValue['category'];
                $yodlee_account_transaction->category_source = $accountTransactionValue['categorySource'];
                $yodlee_account_transaction->high_level_category_id = $accountTransactionValue['highLevelCategoryId'];
                $yodlee_account_transaction->created_date = $accountTransactionValue['createdDate'];
                $yodlee_account_transaction->last_updated = $accountTransactionValue['lastUpdated'];
                $yodlee_account_transaction->description_original = $accountTransactionValue['description']['original'];
                $yodlee_account_transaction->description_simple = $accountTransactionValue['description']['simple'];
                $yodlee_account_transaction->type = $accountTransactionValue['type'];
                $yodlee_account_transaction->sub_type = $accountTransactionValue['subType'];
                $yodlee_account_transaction->is_manual = $accountTransactionValue['isManual'];
                $yodlee_account_transaction->date = $accountTransactionValue['date'];
                $yodlee_account_transaction->transaction_date = $accountTransactionValue['transactionDate'];
                $yodlee_account_transaction->post_date = $accountTransactionValue['postDate'];
                $yodlee_account_transaction->status = $accountTransactionValue['status'];
                $yodlee_account_transaction->running_balance_amount = $accountTransactionValue['runningBalance']['amount'];
                $yodlee_account_transaction->running_balance_currency = $accountTransactionValue['runningBalance']['currency'];
                $yodlee_account_transaction->check_number = $accountTransactionValue['checkNumber'];
                $yodlee_account_transaction->save();
            }
        }

    }

    public function getAccountTransaction(Request $accountDetails) {
        $yodlee_account = Yodlee_account_transaction::select('yodlee_account_transaction.*')
            ->join('yodlee_account','yodlee_account.id','yodlee_account_transaction.account_id')
            ->join('yodlee_login','yodlee_login.yodlee_login_id','yodlee_account.yodlee_login_id')
            ->where('yodlee_account_transaction.account_id',$accountDetails->accountId)
            ->where('yodlee_login.staff_id',$accountDetails->staffId)
            ->get();
        return json_encode($yodlee_account);

    }

}

