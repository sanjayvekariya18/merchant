<?php

namespace App\Http\Controllers;

use App\Http\Controllers\PermissionsController;
use App\Http\Traits\PermissionTrait;
use App\Postal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

/**
 * Class Account_typeController.
 *
 * @author  The scaffold-interface created at 2018-02-18 01:01:05pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class CategoriesController extends PermissionsController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->permissionDetails('Categories', 'access')) {
            $permissions   = $this->getPermission("Categories");
            return view('categories.index', compact('permissions'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
