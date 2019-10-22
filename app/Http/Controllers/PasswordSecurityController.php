<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\PasswordSecurity;
use App\Http\Traits\PermissionTrait;
use Auth;
use Hash;
use App\Staff;
use Validator;
use User;
use DB;

class PasswordSecurityController extends Controller
{

	use PermissionTrait;

	public function show2faForm(Request $request){
		$user = Auth::user();
		$passwordSecurity = PasswordSecurity::where("user_id",$user->user_id)->get()->first();
		if($passwordSecurity->google2fa_enable){
			$orignalTable = $this->getTableType($user->identity_table_id)->table_code;
			$identityTableId = $this->getIdentityTableType($orignalTable,$user->identity_id)->identity_table_id;
			$identityTable = $this->getTableType($identityTableId)->table_code;

			$userInfo = DB::table($orignalTable)
				->select($identityTable.'.*')
				->join($identityTable,$identityTable.'.identity_id',$orignalTable.'.identity_id')
				->where($orignalTable.'.identity_id',$user->identity_id)
				->get()->first();

			$google2fa_url = "";
			$google2fa = app('pragmarx.google2fa');
			PasswordSecurity::where('user_id',$user->user_id)
					->update(['google2fa_secret' => $google2fa->generateSecretKey()]);
					
			if($user->passwordSecurity()->exists()){
				$google2fa = app('pragmarx.google2fa');
				$google2fa->setAllowInsecureCallToGoogleApis(true);
				$google2fa_url = $google2fa->getQRCodeGoogleUrl(
					$userInfo->identity_name,
					$userInfo->identity_email,
					$user->passwordSecurity->google2fa_secret
				);
			}
			$data = array(
				'user' => $user,
				'google2fa_url' => $google2fa_url
			);
			return view('auth.2fa')->with('data', $data);
		}else{
			return redirect('index');
		}	
	}

	public function reauthenticate()
	{
		$user = Auth::user();

		// Initialise the 2FA class
		$google2fa = app('pragmarx.google2fa');

		PasswordSecurity::where('user_id',$user->user_id)
						->update(['google2fa_secret' => $google2fa->generateSecretKey()]);

		return redirect('/2fa')->with('success',"Secret Key is generated, Please verify Code to Enable 2FA");
	}

	public function generate2faSecret(Request $request){
		$user = Auth::user();
		$passwordSecurityExist = PasswordSecurity::where("user_id",$user->user_id)->get()->count();

		// Initialise the 2FA class
		$google2fa = app('pragmarx.google2fa');

		if(!$passwordSecurityExist){		 
			// Add the secret key to the registration data
			PasswordSecurity::create([
				'user_id' => $user->user_id,
				'google2fa_enable' => 0,
				'google2fa_secret' => $google2fa->generateSecretKey(),
			]);
		}else{
			PasswordSecurity::where('user_id',$user->user_id)
					->update(['google2fa_secret' => $google2fa->generateSecretKey()]);
		}		
	 
		return redirect('/2fa')->with('success',"Secret Key is generated, Please verify Code to Enable 2FA");
	}

	public function enable2fa(Request $request){
		$user = Auth::user();
		$google2fa = app('pragmarx.google2fa');
		$secret = $request->input('verify-code');
		$valid = $google2fa->verifyKey($user->passwordSecurity->google2fa_secret, $secret);
		if($valid){
			$user->passwordSecurity->google2fa_enable = 1;
			$user->passwordSecurity->save();
			return redirect("home");
			// return redirect('2fa')->with('success',"2FA is Enabled Successfully.");
		}else{
			return redirect('2fa')->with('error',"Invalid Verification Code, Please try again.");
		}
	}

	public function disable2fa(Request $request){
		if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
			// The passwords matches
			return redirect()->back()->with("error","Your  password does not matches with your account password. Please try again.");
		}
		
		$this->validate($request, [
			'current-password' => 'required',
		]);

		/*$validatedData = $request->validate([
			'current-password' => 'required',
		]);*/
		$user = Auth::user();
		$user->passwordSecurity->google2fa_enable = 0;
		$user->passwordSecurity->save();
		return redirect('/2fa')->with('success',"2FA is now Disabled.");
	}
}
