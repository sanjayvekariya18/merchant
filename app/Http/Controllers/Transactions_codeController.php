<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Transactions_code;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;

use URL;
use Auth;
use Session;
use DB;
use Redirect;


/**
 * Class Transactions_codeController.
 *
 * @author  The scaffold-interface created at 2018-02-22 08:12:05pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Transactions_codeController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Transactions_code');

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
        if($this->permissionDetails('Transactions_code','access')) {
                       
            $permissions = $this->getPermission("Transactions_code");
            
            if($this->merchantId == 0){
                $transactions_codes = Transactions_code::All();
                return view('transactions_code.index',compact('transactions_codes','permissions'));
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
        if($this->permissionDetails('Transactions_code','add')) {
            $title = 'Create - transactions_code';
            return view('transactions_code.create');
        } else {
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
        $transactions_code = new Transactions_code();

        $transactions_code->code_random = $request->code_random;

        $transactions_code->save();

        Session::flash('type', 'success');
        Session::flash('msg', 'Transaction Code Successfully Created');
        if ($request->submitBtn === "Save") {
           return redirect('transactions_code/'. $transactions_code->code_id . '/edit');
        } else {
           return redirect('transactions_code');
        }
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
        $title = 'Show - transactions_code';

        if($request->ajax())
        {
            return URL::to('transactions_code/'.$id);
        }

        $transactions_code = Transactions_code::findOrfail($id);
        return view('transactions_code.show',compact('title','transactions_code'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        if($this->permissionDetails('Transactions_code','manage')) {
            $transactions_code = Transactions_code::findOrfail($id);
            return view('transactions_code.edit',compact('transactions_code'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
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
        $transactions_code = Transactions_code::findOrfail($id);
    	        
        $transactions_code->code_random = $request->code_random;
        
        $transactions_code->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Transaction Code Successfully Updated');

        if ($request->submitBtn === "Save") {
           return redirect('transactions_code/'. $transactions_code->code_id . '/edit');
        } else {
           return redirect('transactions_code');
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
        if($this->permissionDetails('Transactions_code','delete')) {
         	$transactions_code = Transactions_code::findOrfail($id);
         	$transactions_code->delete();
            Session::flash('type', 'success');
            Session::flash('msg', 'Transaction Code Successfully Deleted');
            return redirect('transactions_code');
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getAllTransactionCode() {
        $transactionsCode = Transactions_code::all();
        return json_encode($transactionsCode);
    }

    public function updateTransactionCode(Request $request) {
        $codeId = $request->code_id;
        $key = $request->key;
        $value = $request->value;
        if($key === "code_random") {
            $transactionCode = Transactions_code::findOrfail($codeId);
            $transactionCode->code_random = $value;
            $transactionCode->save();
        }
    }
}
