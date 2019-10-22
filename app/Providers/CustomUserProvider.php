<?php

namespace App\Providers;
use App\Hase_user;
use App\Portal_password;
use App\User; 
use Carbon\Carbon;
use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\PermissionTrait;
use App\Staff;
use App\Location_list;
use App\Identity_group_list;
use DB;
use URL;
use Session;

class CustomUserProvider implements UserProvider {

    const STAFF_TABLE_IDENTITY_TYPE = 35;
    const CLEAR_PASSWORD_TIME_DIFFERENCE = 15;
    const TIME_DIFFERENCE_SECONDS_VALUE = 60;
    const CLEAR_PASSWORD_VALUE = 2;
    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        // TODO: Implement retrieveById() method.

        $qry = Portal_password::where('user_id','=',$identifier);

        if($qry->count() >0)
        {
            $user = $qry->select('user_id', 'username', 'password','identity_id','identity_table_id','clear_password','clear_password_timestamp')->first();

            $attributes = array(
                'id' => $user->user_id,
                'username' => $user->username,
                'password' => $user->password,
                'staff_id' => $user->identity_id,
                'identity_table_id' => $user->identity_table_id,
            );

            return $user;
        }
        return null;
    }

    /**
     * Retrieve a user by by their unique identifier and "remember me" token.
     *
     * @param  mixed $identifier
     * @param  string $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        // TODO: Implement retrieveByToken() method.
        $qry = Portal_password::where('user_id','=',$identifier);

        if($qry->count() >0)
        {
            $user = $qry->select('user_id', 'username', 'password','identity_id','identity_table_id')->first();

            $attributes = array(
                'user_id' => $user->user_id,
                'username' => $user->username,
                'password' => $user->password,
                'staff_id' => $user->identity_id,
                'identity_table_id' => $user->identity_table_id

            );

            return $user;
        }
        return null;
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  string $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {

    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {

        // TODO: Implement retrieveByCredentials() method.
        $qry = Portal_password::where('username','=',$credentials['username']);

        if($qry->count() > 0)
        {
            $user = $qry->select('user_id','username','password','identity_id','identity_table_id','clear_password','clear_password_timestamp')->first();
            return $user;
        }

        return null;

    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // TODO: Implement validateCredentials() method.
        // we'll assume if a user was retrieved, it's good
        // DIFFERENT THAN ORIGINAL ANSWER
        $authFlag = false;

        if($user->clear_password != null && $user->username == $credentials['username'] && $user->clear_password == $credentials['password']){
            
            if($user->clear_password_timestamp != null && $user->clear_password_timestamp != 0){
                $clear_password_added_datetime = json_decode(PermissionTrait::covertToLocalTz($user->clear_password_timestamp));
                $current_datetime              = json_decode(PermissionTrait::covertToLocalTz(time()));
                $current_password_datetime     = strtotime($current_datetime->time);
                $clear_password_added_datetime = strtotime($clear_password_added_datetime->time);
                $timeDiffrence                 = round(abs($clear_password_added_datetime - $current_password_datetime) / self::TIME_DIFFERENCE_SECONDS_VALUE, self::CLEAR_PASSWORD_VALUE);
                if ($timeDiffrence > self::CLEAR_PASSWORD_TIME_DIFFERENCE) {
                    $userSet = array(
                    'clear_password' => uniqid(),
                    'clear_password_timestamp' =>time(),
                    );
                    DB::table('portal_password')->where('user_id',$user->user_id)->update($userSet);
                    Session::flash('type', 'error');
                    Session::flash('msg', 'This password is stale. Request new password from Administrator.');
                    return false;
                }
            } else {
                    $userSet = array(
                    'clear_password' => uniqid(),
                    'clear_password_timestamp' =>time(),
                    );
                    DB::table('portal_password')->where('user_id',$user->user_id)->update($userSet);

            }
            $clear_password_added_datetime = json_decode(PermissionTrait::covertToLocalTz($user->clear_password_timestamp));
            $currentDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now());
            $clearPasswordDate = date('Y-m-d H:i:s', strtotime($clear_password_added_datetime->date." ".$clear_password_added_datetime->time));

            $diff_in_hours = $currentDate->diffInHours($clearPasswordDate);
            if($diff_in_hours > 12){

                $userSet = array(
                'clear_password' => null,
                'clear_password_timestamp' => null
                );
            
                DB::table('portal_password')
                ->where('user_id',$user->user_id)
                    ->update($userSet);
 
                $authFlag = false;
            }else{
                $authFlag = true;
            }
        }else if($user->username == $credentials['username'] && Hash::check($credentials['password'], $user->getAuthPassword())){
            $authFlag = true;
        }else{
            $authFlag = false;
        }

        if($authFlag)
        {
            $identityRole = Identity_group_list::
                where('identity_group_list.identity_id',$user->identity_id)
                ->where('identity_group_list.identity_table_id','=',$user->identity_table_id)
                ->select('identity_group_list.group_id as group_id')
                ->get()->first();
            $identity_table_name = DB::table('identity_table_type')
                                ->select('table_code')
                                ->where('type_id',$user->identity_table_id)
                                ->first()->table_code;
            $merchantDetails = Portal_password::join($identity_table_name,$identity_table_name.'.identity_id','=','portal_password.identity_id')
                ->where('portal_password.identity_id',$user->identity_id)
                ->where('portal_password.identity_table_id',$user->identity_table_id)
                ->select($identity_table_name.'.merchant_id')
                ->get()->first();

            /*$staffInfo = Staff::select('identity_group_list.group_id','staff.merchant_id','identity_staff.identity_name','staff.identity_id')
                ->leftjoin('identity_staff','staff.identity_id','identity_staff.identity_id')
                ->join('identity_group_list','identity_group_list.identity_id','=','staff.identity_id')
                ->where('identity_group_list.identity_table_id',$user->identity_table_id)
                ->where('staff.staff_id',$user->identity_id)
                ->get()->first();*/

            $locationInfo = Location_list::
                select('location_list.postal_id as location_id')
                ->where('identity_id','=',$user->identity_id)
                ->where('location_list.identity_table_id',$user->identity_table_id)
                ->get()->first();
            if($locationInfo)
            {
                $locationId = $locationInfo->location_id;
            } else {
                $locationId = 0;
            }
            $staffUrl='hase_'.$identity_table_name;
            if($identity_table_name == 'customers'){
               $staffUrl='hase_customer'; 
            }
            $sessionArray = array(
                'userId' => $user->user_id,
                'staffId' => $user->identity_id,
                'identity_table_id'=> $user->identity_table_id,
                'staffName' => $user->username,
                'merchantId' => $merchantDetails->merchant_id,
                'locationId' => $locationId,
                'role' => $identityRole->group_id,
                'merchantType' => 10,
                'userTranslationPriority' => 0,
                'staffUrl'=>$staffUrl
            );         
            session($sessionArray);   
            $user->save();

            // CHECK PERMISSION FOR LOGIN USER

            $permissions = PermissionTrait::getUserPermissions();            
            if(!count($permissions)){
                Session::flash('type', 'error');
                Session::flash('msg', 'Module Permission disabled'); 
                return false;
            }

            // Call Activity Log Function

            $staffUrl = "/".session('staffUrl');
            $message = "<a href='".URL::to($staffUrl)."'>".session('staffName')."</a> <strong>logged</strong> in";
            $action = "logged in";
            PermissionTrait::addActivityLog($action,$message);

            return true;
        }
        return false;
    }
}