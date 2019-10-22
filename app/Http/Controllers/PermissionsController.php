<?php

namespace App\Http\Controllers;

use App\Http\Traits\PermissionTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Redirect;
use Session;
use Auth;
/**
 * Class Account_typeController.
 *
 * @author  The scaffold-interface created at 2018-02-18 01:01:05pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class PermissionsController extends Controller
{
    use PermissionTrait;
    
    protected $merchantId;
    protected $roleId;
    protected $locationId;
    protected $staffId;
    protected $userId;
    protected $staffUrl;
    protected $staffName;
    protected $identityTableId;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('2fa');
        $this->middleware(function ($request, $next) {
            $this->merchantId      = session()->has('merchantId') ? session()->get('merchantId') : "";
            $this->roleId          = session()->has('role') ? session()->get('role') : "";
            $this->locationId      = session()->has('locationId') ? session()->get('locationId') : "";
            $this->staffId         = session()->has('staffId') ? session()->get('staffId') : "";
            $this->identityTableId = session()->has('identity_table_id') ? session()->get('identity_table_id') : "";
            $this->userId          = session()->has('userId') ? session()->get('userId') : "";
            $this->staffUrl        = session()->has('staffUrl') ? session()->get('staffUrl') : "";
            $this->staffName       = session()->has('staffName') ? session()->get('staffName') : "";

            $user = Auth::user();
            if (is_null($user->password) || empty($user->password)) {
                Redirect::to('password')->with('message', 'Update password')->send();
            }
            return $next($request);
        });
    }
}
