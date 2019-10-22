<?php

namespace App\Helpers;

use App\Customer;
use App\Http\Traits\PermissionTrait;
use App\Helpers\ConnectionManager;
use App\Identity_customer;
use App\Identity_group_list;
use App\Location_list;
use App\Merchant;
use App\Merchant_customer_list;
use App\PasswordSecurity;
use App\Portal_password;
use App\Portal_social_profile;
use Auth;
use Carbon\Carbon;
use DB;
use Redirect;
use Session;
use URL;

const CUSTOMER_IDENTITY_TYPE_ID  = "8";
const CUSTOMER_IDENTITY_TABLE_ID = "3";
const CUSTOMER_TABLE_ID          = "4";
const MERCHANT_CODE_SUFFIX       = "-merc";
const CUSTOMER_CODE_SUFFIX       = "-cust-";
const DEFAULT_CUSTOMER_GROUP_ID  = 9;
const INDEX_ZERO                 = 0;
const INDEX_ONE                  = 1;
const INDEX_FIVE                 = 5;
const INDEX_EIGHT                = 8;
const INDEX_TEN                  = 10;

class SocialAuth
{
    private $codeCounter;

    public function __construct()
    {
        $this->codeCounter = INDEX_ZERO;
    }

    public function addSocialCustomer($userApiID, $firstName, $lastName, $email)
    {
        if (!$this->checkUsername($userApiID)) {
            $ip = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR'] ?: ($_SERVER['HTTP_X_FORWARDED_FOR'] ?: $_SERVER['HTTP_CLIENT_IP']):'127.0.0.1';

            $ipInformation          = PermissionTrait::getUserIpAddress($ip);
            $hase_identity          = new Identity_customer();
            $hase_customer          = new Customer();
            $hase_user              = new Portal_password();
            $merchant_customer_list = new Merchant_customer_list();

            $salt           = uniqid();
            $clear_password = uniqid();
            $merchant_id    = INDEX_ONE;

            // GET MERCHANT INFORMATION

            $merchant = Merchant::
                distinct()
                ->select('merchant.*', 'identity_merchant.identity_code as merchant_code')
                ->leftjoin('identity_merchant', 'identity_merchant.identity_id', '=', 'merchant.identity_id')
                ->where('merchant.merchant_id', '=', $merchant_id)
                ->get()->first();

            $merchantCode    = $merchant->merchant_code;
            $merchantCodeNew = str_replace(MERCHANT_CODE_SUFFIX, CUSTOMER_CODE_SUFFIX, $merchantCode);
            $customerCode    = substr($firstName, INDEX_ZERO, INDEX_ONE) . substr($lastName, INDEX_ZERO, INDEX_FIVE);
            $customerCodeNew = $merchantCodeNew . $customerCode;

            // GENERATE CUSTOMER CODE

            $codeName = $this->generateCode($customerCodeNew);

            // ADD RECORD IN CUSTOMER IDENTITY

            $hase_identity->identity_code     = strtolower($codeName);
            $hase_identity->identity_name     = $firstName . " " . $lastName;
            $hase_identity->identity_email    = (filter_var($email, FILTER_VALIDATE_EMAIL)) ? $email : "";
            $hase_identity->identity_type_id  = CUSTOMER_IDENTITY_TYPE_ID;
            $hase_identity->identity_table_id = CUSTOMER_TABLE_ID;
            $hase_identity->save();

            // CURRENT CUSTOMER IDENTITY ID

            $identityId = $hase_identity->identity_id;

            // ADD RECORD IN CUSTOMER

            $hase_customer->identity_id       = $identityId;
            $hase_customer->identity_table_id = CUSTOMER_IDENTITY_TABLE_ID;
            $hase_customer->fname             = $firstName;
            $hase_customer->lname             = $lastName;
            $hase_customer->salt              = $salt;
            $hase_customer->newsletter        = INDEX_ZERO;
            $hase_customer->ip_address        = isset($ipInformation['ip']) ? $ipInformation['ip'] : "";
            $hase_customer->date_added        = Carbon::now('Asia/kolkata');
            $hase_customer->status            = INDEX_ONE;
            $hase_customer->merchant_id       = $merchant_id;
            $hase_customer->save();

            // CURRENT CUSTOMER ID

            $customerId = $hase_customer->customer_id;

            // ADD RECORD IN MERCHANT CUSTOMER LIST

            $merchant_customer_list->merchant_id = $merchant_id;
            $merchant_customer_list->customer_id = $customerId;
            $merchant_customer_list->save();

            // ADD DEFAULT LOCATION CITY ID

            $location_list                    = new Location_list();
            $location_list->identity_id       = $identityId;
            $location_list->identity_table_id = CUSTOMER_TABLE_ID;
            $location_list->location_city_id  = INDEX_ZERO;
            $location_list->postal_id         = INDEX_ZERO;
            $location_list->priority          = INDEX_ZERO;
            $location_list->status            = INDEX_ONE;
            $location_list->save();

            // ADD USER DETAIL FOR LOGIN

            $hase_user->identity_id         = $hase_customer->identity_id;
            $hase_user->identity_table_id   = CUSTOMER_TABLE_ID;
            $hase_user->username            = substr($userApiID, INDEX_ZERO, INDEX_EIGHT);
            $hase_user->clear_password      = $clear_password;
            $hase_user->clear_password_timestamp =time();
            $hase_user->save();

            // ADD DEFAULT CUSTOMER GROUP AS REGULAR CUSTOMER.

            $identity_group_list                    = new Identity_group_list();
            $identity_group_list->group_id          = DEFAULT_CUSTOMER_GROUP_ID;
            $identity_group_list->identity_table_id = CUSTOMER_TABLE_ID;
            $identity_group_list->identity_id       = $identityId;
            $identity_group_list->save();

            // CREATE GOOGLE 2FA SECREAT KEY FOR CUSTOMER.

            $google2fa = app('pragmarx.google2fa');

            $password_security = new PasswordSecurity();

            $password_security->user_id          = $hase_user->user_id;
            $password_security->google2fa_enable = INDEX_ZERO;
            $password_security->google2fa_secret = $google2fa->generateSecretKey();
            $password_security->save();

            return $hase_user->user_id;

        } else {
            $hase_user = DB::table('portal_social_profile')
                ->where('user_api_id', $userApiID)
                ->get()->first();

            return $hase_user->user_id;
        }
    }

    public function loginSocialUser($customerUserId, $connectorName, $oauthToken, $secretOauthToken, $userApiID, $displayName, $avatar, $email, $gender = null, $city = null)
    {
    	try {
	        // LOGIN FOR CURRENT USER.

	        $staffUrl = "hase_customer";

	        $userInfo = Portal_password::where('user_id', $customerUserId)->get()->first();

	        Auth::loginUsingId($userInfo->user_id);

	        $identity_table_name = DB::table('identity_table_type')
	            ->select('table_code')
	            ->where('type_id', $userInfo->identity_table_id)
	            ->first()->table_code;

	        $merchantDetails = Portal_password::join($identity_table_name, $identity_table_name . '.identity_id', '=', 'portal_password.identity_id')
	            ->where('portal_password.identity_id', $userInfo->identity_id)
	            ->where('portal_password.identity_table_id', $userInfo->identity_table_id)
	            ->select($identity_table_name . '.merchant_id')
	            ->get()->first();

	        $locationInfo = Location_list::
	            select('location_list.postal_id as location_id')
	            ->where('identity_id', '=', $userInfo->identity_id)
	            ->where('location_list.identity_table_id', $userInfo->identity_table_id)
	            ->get()->first();

	        if ($locationInfo) {
	            $locationId = $locationInfo->location_id;
	        } else {
	            $locationId = INDEX_ZERO;
	        }

	        $sessionArray = array(
	            'userId'                  => $userInfo->user_id,
	            'staffId'                 => $userInfo->identity_id,
	            'identity_table_id'       => $userInfo->identity_table_id,
	            'staffName'               => $userInfo->username,
	            'merchantId'              => $merchantDetails->merchant_id,
	            'locationId'              => $locationId,
	            'role'                    => DEFAULT_CUSTOMER_GROUP_ID,
	            'merchantType'            => INDEX_TEN,
	            'userTranslationPriority' => INDEX_ZERO,
	            'staffUrl'                => $staffUrl,
	        );

	        session($sessionArray);

	        // Call Activity Log Function

	        $staffUrl = "/" . session('staffUrl') . "/" . session('staffId') . "/edit";
	        $message  = "<a href='" . URL::to($staffUrl) . "'>" . session('staffName') . "</a> <strong>logged</strong> in";
	        $action   = "logged in";
	        PermissionTrait::addActivityLog($action, $message);
	        PermissionTrait::storeConnectorUserDetails($connectorName, $userApiID, $displayName, $oauthToken, $secretOauthToken, $avatar, $gender, $city);

	        return redirect('/home');
	    } catch (\Exception $e) {

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            Session::flash("type", "error");
            Session::flash("msg", $exceptionMessage);
            return redirect('/login');
        }    
    }

    public function checkUsername($userApiID)
    {
        $user = Portal_social_profile::
            where('user_api_id', $userApiID)
            ->get()
            ->first();

        return (isset($user->social_user_id)) ? true : false;
    }

    public function generateCode($orignalCodeName)
    {

        if ($this->codeCounter == INDEX_ZERO) {
            $codeName = $orignalCodeName;
        } else {
            $codeName = $orignalCodeName . $this->codeCounter;
        }

        $code_exist = Identity_customer::select('*')
            ->where('identity_code', $codeName)
            ->get()->first();

        if (!count($code_exist)) {

            return $codeName;

        } else {
            $this->codeCounter = $this->codeCounter + INDEX_ONE;
            return $this->generateCode($orignalCodeName);
        }
    }

    public function fetchUserInfo($user, $socialConnectorName)
    {

        $userInfo = array();
        $userData = $user->getRaw();

        $userInfo['oauthToken']       = $user->token;
        $userInfo['secretOauthToken'] = isset($user->refreshToken)?$user->refreshToken:"";
        $userInfo['userApiID']        = $user->id;
        $userInfo['displayName']      = $user->name;
        $userInfo['avatar']            = $user->avatar;
        $userInfo['email']            = $user->email;
        $userInfo['gender']           = "";
        $userInfo['city']             = "";

        switch ($socialConnectorName) {
            case 'google':
                $userInfo['firstName'] = $userData['name']['givenName'];
                $userInfo['lastName']  = $userData['name']['familyName'];
                $userInfo['gender']    = isset($userData['gender']) ? $userData['gender'] : "";
                $userInfo['avatar']     = $user->avatar_original;
                $tempGoogleToken['access_token'] = $user->token;
                $tempGoogleToken['refresh_token'] = $user->refreshToken;
                $tempGoogleToken['expires_in'] = $user->expiresIn;
                $userInfo['oauthToken']       = json_encode($tempGoogleToken);
                break;
            case 'facebook':
                $userName              = explode(" ", $userData['name']);
                $userInfo['firstName'] = isset($userName[INDEX_ZERO]) ? $userName[INDEX_ZERO] : "";
                $userInfo['lastName']  = isset($userName[INDEX_ONE]) ? $userName[INDEX_ONE] : "";
                $userInfo['gender']    = isset($userData['gender']) ? $userData['gender'] : "";
                $userInfo['avatar']     = $user->avatar_original;
                break;
            case 'linkedin':
                $userInfo['firstName'] = $userData['firstName'];
                $userInfo['lastName']  = $userData['lastName'];
                $userInfo['avatar']     = $user->avatar_original;
                break;
            case 'twitter':
                $userInfo['avatar'] = $user->avatar_original;
            case 'github':
            case 'meetup':
            case 'instagram':
                $userName              = explode(" ", $userInfo['displayName']);
                $userInfo['firstName'] = isset($userName[INDEX_ZERO]) ? $userName[INDEX_ZERO] : "";
                $userInfo['lastName']  = isset($userName[INDEX_ONE]) ? $userName[INDEX_ONE] : "";
                break;
            case 'eventbrite':
                $userInfo['firstName'] = $userData['first_name'];
                $userInfo['lastName']  = $userData['last_name'];
                if(empty($userInfo['avatar'])){
                    $userInfo['avatar'] = "https://cdn.evbuc.com/images/".$userInfo['avatar']."/".$userInfo['userApiID']."/1/original.jpg";
                }
                break;
            case 'flickr':
                $userInfo['secretOauthToken'] = isset($user->tokenSecret)?$user->tokenSecret:"";
        $userInfo['userApiID']        = $user->id;
                $userInfo['userApiID'] = $userData['nsid'];
                $userName              = explode(" ", $userInfo['displayName']);
                $userInfo['firstName'] = isset($userName[INDEX_ZERO]) ? $userName[INDEX_ZERO] : "";
                $userInfo['lastName']  = isset($userName[INDEX_ONE]) ? $userName[INDEX_ONE] : "";

                $userInfo['avatar'] = "http://farm" . $userData['iconfarm'] . ".staticflickr.com/" . $userData['iconserver'] . "/buddyicons/" . $userData['nsid'] . ".jpg";
                break;
            case 'foursquare':
                $userInfo['firstName'] = $userData['firstName'];
                $userInfo['lastName']  = $userData['lastName'];
                $userInfo['gender']    = $userData['gender'];
                $userInfo['city']      = $userData['homeCity'];
                break;
            case 'strava':
                $userInfo['firstName'] = $userData['firstname'];
                $userInfo['lastName']  = $userData['lastname'];
                $userInfo['gender']    = isset($userData['sex']) ? ($userData['sex'] === 'M') ? 'Male' : 'Female' : '';
                $userInfo['city']      = $userData['city'];
                break;
            case 'freelancer':
                $userInfo['displayName'] = $user->nickname;
                $userInfo['firstName']   = isset($userData['result']['first_name']) ? $userData['result']['first_name'] : $userData['result']['display_name'];
                $userInfo['lastName']    = isset($userData['result']['last_name']) ? $userData['result']['last_name'] : "";
                $userInfo['city']        = isset($userData['result']['location']['city']) ? $userData['result']['location']['city'] : "";
                break;
            default:
                break;
        }
        return $userInfo;
    }

    public function loginConnect($user, $socialConnectorName)
    {
        try {

            $userInfo = $this->fetchUserInfo($user, $socialConnectorName);

            if ($userInfo['firstName'] != "") {
                $customerUserId = $this->addSocialCustomer($userInfo['userApiID'], $userInfo['firstName'], $userInfo['lastName'], $userInfo['email']);
                return $this->loginSocialUser($customerUserId, $socialConnectorName, $userInfo['oauthToken'], $userInfo['secretOauthToken'], $userInfo['userApiID'], $userInfo['displayName'], $userInfo['avatar'], $userInfo['email'], $userInfo['gender']);
            } else {
                Session::flash("type", "error");
                Session::flash("msg", "First name not able to fetch from " . $socialConnectorName . " api");
                return redirect('/login');
            }

        } catch (Exception $e) {

            Session::flash("type", "error");
            Session::flash("msg", $e->getMessage());
            return redirect('/login');
        }
    }

    public function socialConnect($user, $socialConnectorName)
    {
        try{              
            $userInfo = $this->fetchUserInfo($user,$socialConnectorName);         

            if($userInfo['firstName'] !=""){
                if(!PermissionTrait::checkUserApiId($userInfo['userApiID'])){
                    $result = PermissionTrait::storeConnectorUserDetails($socialConnectorName,$userInfo['userApiID'],$userInfo['displayName'],$userInfo['oauthToken'],$userInfo['secretOauthToken'],$userInfo['avatar'],$userInfo['gender'],$userInfo['city']);

                    Session::flash('type', $result['type']);
                    Session::flash('msg', $result['message']);
                }else{
                    Session::flash('type', 'error'); 
                    Session::flash('msg', 'User API Already Register With Other User');
                }                            
            }
        }catch (Exception $e) {

            Session::flash("type","error");
            Session::flash("msg",$e->getMessage());
            
        }finally{

            echo "<script>
            localStorage.setItem('type','".Session::pull('type')."');
            localStorage.setItem('msg','".Session::pull('msg')."');   
            window.close();
            </script>";
        }    
    } 

    public function socialDisconnect($socialConnectorName){
        try{
            $userId = session()->has('userId') ? session()->get('userId') :"";
            $result = PermissionTrait::deactivateConnector($userId, $socialConnectorName);

            return $result;

        }catch (Exception $e) {

            Session::flash("type","error");
            Session::flash("msg",$e->getMessage());
            return redirect('/logout');
        }    
    }

}
