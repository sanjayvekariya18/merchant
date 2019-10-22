<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use app\Imports;
use App\Staff;
use App\Http\Traits\PermissionTrait;
use URL;
use Session;
use DB;
use Redirect;
use Auth;
/**
 * Class Hase_importController.
 *
 * @author  The scaffold-interface created at 2017-03-18 03:34:36am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Hase_importController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Hase_import');
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
        
        /*if($this->permissionDetails('Hase_import','access')){*/
            $title = 'Index - hase_import';
            if($this->merchantId === 0){

                $hase_imports = Imports::
                                join('import_status','imports.import_status_id','=','import_status.import_status_id')
                                ->join('staffs','imports.staff_id','=','staffs.staff_id')->get();
            }else{
                $hase_imports = Imports::
                                join('import_status','imports.import_status_id','=','import_status.import_status_id')
                                ->join('staffs','imports.staff_id','=','staffs.staff_id')
                                ->where('merchant_id',"=",$this->merchantId)->get();
            }

            return view('hase_import.index',compact('hase_imports','title'));
        /*}else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }*/
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        /*if($this->permissionDetails('Hase_import','add')){*/

            $title = 'Create - hase_import';
            return view('hase_import.create');

        /*}else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }*/
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $hase_import = new Imports();
        $hase_import->merchant_id = $this->merchantId;
        $hase_import->staff_id = Auth::user()->staff_id;
        $hase_import->title = $request->title;
        $hase_import->import_status_id = 1;

        switch ($request->title) {
            case 'Resturant Merchant':
                $slug="1.Restaurant_merchant.xlsx";
                break;
            case 'Resturant Working Hours':
                $slug="2.Restaurant_working_hour.xlsx";
                break;
            case 'Resturant Holiday Working Hours':
                $slug="3.Restaurant_holiday_hour.xlsx";
                break;
            case 'Resturant Menus':
                $slug="4.Restaurant_dish.xlsx";
                break;
            case 'Shop Merchant':
                $slug="5.Shop_merchant.xlsx";
                break;        
            case 'Shop Working Hours':
                $slug="6.Shop_working_hour.xlsx";
                break;
            case 'Shop Holiday Working Hours':
                $slug="7.Shop_holiday_hour.xlsx";
                break;
            case 'Shop Menus':
                $slug="8.Shop_product.xlsx";
                break;            
            default:                
                break;
        }

        if($request->file('upload_file')){          
            
            $slugPath = asset('/assets/imports/').'/'.$slug;
            $request->file('upload_file')->move(public_path('assets/imports'), $slug);
            $hase_import->slug = $slugPath;
        }

        $hase_import_record_exist = Imports::where('title', '=', $request->title)->count();

        if (!$hase_import_record_exist) {
            $hase_import->save();    
        }        

        Session::flash('type', 'success'); 
        Session::flash('msg', 'File Successfully Uploaded');
        
        if ($request->submitBtn === "Save") {
           return redirect('hase_import/create');
        }else{
           return redirect('hase_import');
        }
    }

    
    /**
     * Show the form for editing the specified resource.
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        return redirect('hase_import');

        $title = 'Edit - hase_import';
                
        $hase_import = Imports::findOrfail($id);

        return view('hase_import.edit',compact('title','hase_import'));
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
        return redirect('hase_import');
        
        $hase_import = Imports::findOrfail($id);
        
        $hase_import->import_id = $request->import_id;
        $hase_import->merchant_id = $this->merchantId;
        $hase_import->staff_id = Auth::user()->staff_id;
        $hase_import->title = $request->title;
        $hase_import->import_status_id = 1;

        if($request->file('upload_file')){

            if (!empty($hase_import->slug)) {
                
                $filePath = $hase_import->slug;

                if (is_file($filePath)) {
                    unlink($filePath);
                }
            }             
           
            $slug = $request->file('upload_file')->getClientOriginalName();
            $slugPath = asset('/assets/imports/').'/'.$slug;
            $request->file('upload_file')->move(public_path('assets/imports'), $slug);
            $hase_import->slug = $slugPath;
        }
        
        $hase_import->save();

        return redirect('hase_import');
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
        $msg = Ajaxis::BtDeleting('Warning!!','Would you like to remove This?','/hase_import/'. $id . '/delete');

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
        $hase_import = Imports::findOrfail($id);

        $filePath = public_path('/assets/imports/').basename($hase_import->slug);

        if (is_file($filePath)) {
            unlink($filePath);
        }       

        $hase_import->delete();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'File Successfully Removed');
        
        return redirect('hase_import');
    }
}
