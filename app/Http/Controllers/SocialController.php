<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Merchant;
use App\Customer;
use App\Social;
use App\Social_apikeys;
use App\Identity_social;
use App\Account;
use App\Account_wallet;
use App\Wallet;
use App\Merchant_account_list;
use App\Customer_account_list;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;

use URL;
use Session;
use DB;
use Redirect;
use Hase;


const SOCIAL_TABLE_ID=31;
const ENCRYPTION_KEY="SoHyper2011!2018";

/**
 * Class SocialController.
 *
 * @author  The scaffold-interface created at 2018-03-04 06:06:54pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class SocialController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Social');

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
        if($this->permissionDetails('Social','access')){
                       
            $permissions = $this->getPermission("Social");            
            return view('social.index',compact('permissions'));

        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
    
    public function getSocials(Request $request)
    {
        
        if(isset($request->skip)){
            $socials=Social::
                select('social.social_id','identity_social.identity_code as social_code','identity_social.identity_name as social_name')
                ->join('identity_social','identity_social.identity_id','social.identity_id')
                ->offset($request->skip)
                ->limit($request->take)
                ->get();
        }else{
            $socials=Social::
                select('social.social_id','identity_social.identity_code as social_code','identity_social.identity_name as social_name')
                ->join('identity_social','identity_social.identity_id','social.identity_id')
                ->get();
        }        

        $total_records=Social::
                join('identity_social','identity_social.identity_id','social.identity_id')
                ->count();        

        $socials_data['socials'] = $socials;
        $socials_data['total'] = $total_records;

        return json_encode($socials_data);
    }    
    
    public function createSocial(Request $request)
    {

        $social_exist = Identity_social::select('*')
                         ->where('identity_code',$request->social_code)
                         ->get()->first();

        if(count($social_exist) == 0){

            $social = new Social();
            $identity_social = new Identity_social();

        }else{

            $social = Social::select('*')
                        ->where('identity_id',$social_exist->identity_id)
                        ->get()->first();

            $identity_social = Identity_social::findOrfail($social->identity_id);
        }
        
        $identity_social->identity_code = $request->social_code;
        $identity_social->identity_name = $request->social_name;

        $identity_social->save();
        $identityID = $identity_social->identity_id;
        
        $social->identity_id = $identityID;
        $social->save();

        return 1;
    } 

    public function updateSocial(Request $request)
    {

        $social = Social::select('*')
                        ->where('social_id',$request->social_id)
                        ->get()->first();

        $identity_social = Identity_social::findOrfail($social->identity_id);


        $identity_social->identity_name = $request->social_name;
        $identity_social->save();

        return 1;
        
    } 

    public function getPersons()
    {
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
            DB::raw("CONCAT('customer_',customers.customer_id) AS person_id"),
            'identity_customer.identity_name as person_name',
            'identity_customer.identity_code as person_code')
        ->join('identity_customer','identity_customer.identity_id','customers.identity_id')
        ->join('customer_account_list','customer_account_list.customer_id','=','customers.customer_id')
        ->where('customers.customer_id','!=',0)
        ->get()->toArray();        

        $people = array_merge($merchants,$customers);

        return json_encode($people);
    }

    public function getSocialAccounts(request $request)
    {
        $requestData = explode('_', $request->person_id);
        $requestTable = $requestData[0];
        $requestId = $requestData[1];

        switch ($requestTable) {
            case 'merchant':
                $accounts = Account::distinct('account.account_id')
                            ->select(
                                'account.account_id','account.account_code_long'
                            )
                            ->join('merchant_account_list','merchant_account_list.staff_account_id','account.account_id')        
                            ->where('merchant_account_list.merchant_id',$requestId)
                            ->get();  
                break;

            case 'customer':

                $accounts = Account::distinct('account.account_id')
                            ->select(
                                'account.account_id','account.account_code_long'
                            )
                            ->join('customer_account_list','customer_account_list.account_id','account.account_id')        
                            ->where('customer_account_list.customer_id',$requestId)
                            ->get();  
                break;

            default:
                $accounts = array();
                break;
        }    

        return json_encode($accounts);
    }

    public function getSocialWallets(request $request)
    {
        $accountId = $request->account_id;

        $wallets = Wallet::select('wallet.wallet_id','wallet.wallet_name')
                    ->join('account_wallet','account_wallet.wallet_id','wallet.wallet_id')
                    ->where('account_wallet.account_id',$accountId)
                    ->get();           

        return json_encode($wallets);
    }

    public function getSocialApiKeys()
    {
        $merchantSocialApiKeys=Social_apikeys::
                select('social_apikeys.*','account.account_code_long','wallet.wallet_name','identity_social.identity_name as social_name','identity_merchant.identity_name as people_name')
                ->join('identity_social','identity_social.identity_id','social_apikeys.identity_id')
                ->join('wallet','wallet.wallet_id','social_apikeys.wallet_id')
                ->join('account_wallet','account_wallet.wallet_id','wallet.wallet_id')
                ->join('account','account.account_id','account_wallet.account_id')
                ->join('merchant_account_list','merchant_account_list.staff_account_id','account.account_id')
                ->join('merchant','merchant.merchant_id','merchant_account_list.merchant_id')
                ->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
                ->get()->toArray();

        $customerSocialApiKeys=Social_apikeys::
                select('social_apikeys.*','account.account_code_long','wallet.wallet_name','identity_social.identity_name as social_name','identity_customer.identity_name as people_name')
                ->join('identity_social','identity_social.identity_id','social_apikeys.identity_id')
                ->join('wallet','wallet.wallet_id','social_apikeys.wallet_id')
                ->join('account_wallet','account_wallet.wallet_id','wallet.wallet_id')
                ->join('account','account.account_id','account_wallet.account_id')
                ->join('customer_account_list','customer_account_list.account_id','account.account_id')
                ->join('customers','customers.customer_id','customer_account_list.customer_id')
                ->join('identity_customer','identity_customer.identity_id','customers.identity_id')
                ->get()->toArray(); 

        $socialApiKeys = array_merge($merchantSocialApiKeys,$customerSocialApiKeys);       

        return json_encode($socialApiKeys);
    } 

    public function getFilterSocialApiKeys(request $request)
    {
        $requestData = explode('_', $request->person_id);
        $requestTable = $requestData[0];
        $requestId = $requestData[1];

        if($requestTable === "merchant"){

            $socialApiKeys=Social_apikeys::
                select('social_apikeys.*','account.account_code_long','wallet.wallet_name','identity_social.identity_name as social_name','identity_merchant.identity_name as people_name')
                ->join('identity_social','identity_social.identity_id','social_apikeys.identity_id')
                ->join('wallet','wallet.wallet_id','social_apikeys.wallet_id')
                ->join('account_wallet','account_wallet.wallet_id','wallet.wallet_id')
                ->join('account','account.account_id','account_wallet.account_id')
                ->join('merchant_account_list','merchant_account_list.staff_account_id','account.account_id')
                ->join('merchant','merchant.merchant_id','merchant_account_list.merchant_id')
                ->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
                ->where('merchant.merchant_id',$requestId)
                ->offset($request->skip)
                ->limit($request->take)
                ->get();

            $total_records=Social_apikeys::
                select('social_apikeys.*','account.account_code_long','wallet.wallet_name','identity_social.identity_name as social_name','identity_merchant.identity_name as people_name')
                ->join('identity_social','identity_social.identity_id','social_apikeys.identity_id')
                ->join('wallet','wallet.wallet_id','social_apikeys.wallet_id')
                ->join('account_wallet','account_wallet.wallet_id','wallet.wallet_id')
                ->join('account','account.account_id','account_wallet.account_id')
                ->join('merchant_account_list','merchant_account_list.staff_account_id','account.account_id')
                ->join('merchant','merchant.merchant_id','merchant_account_list.merchant_id')
                ->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
                ->where('merchant.merchant_id',$requestId)
                ->count();    

        }else if($requestTable === "customer"){

            $socialApiKeys=Social_apikeys::
                join('identity_social','identity_social.identity_id','social_apikeys.identity_id')
                ->join('wallet','wallet.wallet_id','social_apikeys.wallet_id')
                ->join('account_wallet','account_wallet.wallet_id','wallet.wallet_id')
                ->join('account','account.account_id','account_wallet.account_id')
                ->join('customer_account_list','customer_account_list.account_id','account.account_id')
                ->join('customers','customers.customer_id','customer_account_list.customer_id')
                ->join('identity_customer','identity_customer.identity_id','customers.identity_id')
                ->where('customers.customer_id',$requestId)
                ->offset($request->skip)
                ->limit($request->take)
                ->get(); 

            $total_records=Social_apikeys::
                join('identity_social','identity_social.identity_id','social_apikeys.identity_id')
                ->join('wallet','wallet.wallet_id','social_apikeys.wallet_id')
                ->join('account_wallet','account_wallet.wallet_id','wallet.wallet_id')
                ->join('account','account.account_id','account_wallet.account_id')
                ->join('customer_account_list','customer_account_list.account_id','account.account_id')
                ->join('customers','customers.customer_id','customer_account_list.customer_id')
                ->join('identity_customer','identity_customer.identity_id','customers.identity_id')
                ->where('customers.customer_id',$requestId)
                ->count();     
        }

        $socialApiKeys_data['socialApiKeys'] = $socialApiKeys;
        $socialApiKeys_data['total'] = $total_records;

        return json_encode($socialApiKeys_data);
    } 

    public function encryptIt( $keydata ) {
        $dataEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( ENCRYPTION_KEY ), $keydata, MCRYPT_MODE_CBC, md5( md5( ENCRYPTION_KEY ) ) ) );
        return( $dataEncoded );
    }

    public function decryptIt( $keydata ) {
        $dataDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( ENCRYPTION_KEY ), base64_decode( $keydata ), MCRYPT_MODE_CBC, md5( md5( ENCRYPTION_KEY ) ) ), "\0");
        return( $dataDecoded );
    }

    public function createSocialApiKeys(Request $request)
    {

        $social_apikeys = new Social_apikeys();

        $identity_id = Social::where('social_id',$request->social_id)->value('identity_id');
        
        $social_apikeys->wallet_id = $request->wallet_id;
        $social_apikeys->identity_id = $identity_id;
        $social_apikeys->identity_table_id = SOCIAL_TABLE_ID;
        $social_apikeys->apikey_name = $request->apikey_name;
        $social_apikeys->connector_key = $this->encryptIt($request->connector_key);
        $social_apikeys->connector_passcode = $this->encryptIt($request->connector_passcode);
        $social_apikeys->consumer_key = $this->encryptIt($request->consumer_key);
        $social_apikeys->consumer_secret = $this->encryptIt($request->consumer_secret);
        $social_apikeys->access_token = $this->encryptIt($request->access_token);
        $social_apikeys->access_secret = $this->encryptIt($request->access_secret);

        $social_apikeys->save();

        return 1;
    } 

    public function updateSocialApiKeys(Request $request)
    {

        $social_apikeys = Social_apikeys::findOrfail($request->keys_id);
        
        $key = $request->key;
        $value = $request->value;

        if($key === "social_id"){
            $identity_id = Social::where('social_id',$value)->value('identity_id');
            $key = "identity_id";
        }else if($key !== "apikey_name" && $key !== "nonce"){
            $value = $this->encryptIt($value);
        }

        $social_apikeys->$key = $value;
        $social_apikeys->save();

        return 1;        
    }

    public function deleteSocialApiKeys(Request $request)
    {
        
        $keys_id = $request->keys_id;

        $social_apikeys = Social_apikeys::findOrfail($keys_id);
        $social_apikeys->delete();
        return 1;
        
    } 
}
