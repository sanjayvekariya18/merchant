<?php


namespace App\Http\Middleware;
use PragmaRX\Google2FALaravel\Support\Authenticator;
use App\Support\Google2FAAuthenticator;
use Closure;
use App\PasswordSecurity;
use Carbon\Carbon;
 
class Google2FAMiddleware extends Authenticator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $authenticator = app(Google2FAAuthenticator::class)->boot($request);
        $response = $authenticator->isAuthenticated();

        $user = $this->getUser();
        $passwordSecurity = PasswordSecurity::where("user_id",$user->user_id)->get()->first();

        if(!isset($passwordSecurity->user_id)){
            // Add the google2FA record in password security 
            $passwordSecurity = new PasswordSecurity();
            $passwordSecurity->user_id          = $user->user_id;
            $passwordSecurity->google2fa_enable = 0;
            $passwordSecurity->google2fa_secret = NULL;
            $passwordSecurity->save();
        }

        if($passwordSecurity->google2fa_enable && is_null($passwordSecurity->google2fa_secret)){
            return redirect('/2fa');
        }

        if ($response) {
            if(!empty($user->password)){
                $user->clear_password = null;
                $user->clear_password_timestamp = null;
            }else{
                $user->clear_password = uniqid();
                $user->clear_password_timestamp = time();                
            }  
            $user->save();
            return $next($request);
        }
        return $authenticator->makeRequestOneTimePasswordResponse();
    }
}