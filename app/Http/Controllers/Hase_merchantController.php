<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Http\Controllers\ProductApprovalController;
use Carbon\Carbon;
use App\Merchant;
use App\Approval;
use App\Merchant_stage;
use App\Merchant_type;
use App\Merchant_type_list;
use App\Merchant_type_list_stage;
use App\identity_merchant;
use App\identity_merchant_stage;
use App\Http\Traits\PermissionTrait;
use URL;
use Session;
use DB;
use Redirect;

const MERCHANT_IDENTITY_TYPE_ID=1;
const MERCHANT_IDENTITY_TABLE_ID=7;
const MERCHANT_TABLE_ID=8;
const MERCHANT_CODE_SUFFIX = "-merc";


/**
 * Class Hase_merchantController.
 *
 * @author  The scaffold-interface created at 2017-03-11 03:14:10am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Hase_merchantController extends PermissionsController
{
	use PermissionTrait;

	public function __construct()
	{
		parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Hase_merchant');

        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
        
		$this->request_table_live = 'merchant';
		$this->child_request_table_live = 'merchant_type_list';
		$this->request_table_stage = 'merchant_stage';
		$this->child_request_table_stage = 'merchant_type_list_stage';
		$this->identity_request_table_live = 'identity_merchant';
		$this->identity_request_table_stage = 'identity_merchant_stage';
		$this->productApproval = new ProductApprovalController();
		$this->codeCounter = 0;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return  \Illuminate\Http\Response
	 */

	public function index()
	{
		$merchantType = session()->has('merchantType') ? session()->get('merchantType') :"";
		$searchMerchant = '';
		if($this->permissionDetails('Hase_merchant','access')){
						
			$permissions = $this->getPermission("Hase_merchant");

			$merchant_parent_types = Merchant_type::all()->where('merchant_parent_id',0);
			
			if($this->merchantId == 0){

				$hase_merchants = Merchant::
									distinct()
									->select('merchant.*','identity_merchant.identity_name as merchant_name','identity_merchant.identity_logo as merchant_logo','identity_merchant.identity_logo_compact as merchant_logo_compact','identity_merchant.identity_email as merchant_email','identity_merchant.identity_website as merchant_website')
									->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
									->where('merchant.merchant_id','!=',0)
									->where('merchant.merchant_type_id',$merchantType)
									->paginate(25);
									
			}else{
				$hase_merchants = Merchant::
									distinct()
									->select('merchant.*','identity_merchant.identity_name as merchant_name','identity_merchant.identity_logo as merchant_logo','identity_merchant.identity_logo_compact as merchant_logo_compact','identity_merchant.identity_email as merchant_email','identity_merchant.identity_website as merchant_website')
									->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')						
									->where('merchant.merchant_id',$this->merchantId)
									->paginate(25);
			}

			return view('hase_merchant.index',compact('hase_merchants','permissions','merchant_parent_types','merchantType','searchMerchant'));
		}else{
			return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
		}
	}

	/**
	 * search resource in storage.
	 *
	 * @param    \Illuminate\Http\Request  $request
	 * @return  \Illuminate\Http\Response
	 */
	public function search(Request $request)
	{
		$searchMerchant =  trim($request->search_merchant);
        $merchantId = session()->has('merchantId') ? session()->get('merchantId') : '';
		$roleId = session()->has('role') ? session()->get('role') : '';
		$locationId = session()->has('locationId') ? session()->get('locationId') :"";
		$merchantType = session()->has('merchantType') ? session()->get('merchantType') :"";
		$merchant_parent_types = Merchant_type::all()->where('merchant_parent_id',0);
		if($searchMerchant !== "") {
		  if($merchantId == 0) {

		      $hase_merchants = Merchant::
		          distinct()
		          ->select('merchant.*','identity_merchant.identity_name as merchant_name','identity_merchant.identity_logo as merchant_logo','identity_merchant.identity_logo_compact as merchant_logo_compact','identity_merchant.identity_email as merchant_email','identity_merchant.identity_website as merchant_website')
		          ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
		          ->where('merchant.merchant_id','!=',0)
		          ->where('merchant.merchant_type_id',$merchantType)
		          ->where('identity_merchant.identity_name', 'LIKE', '%' . $searchMerchant . '%' )
		          ->paginate(25)->setPath('');
		  } else {

		      $hase_merchants = Merchant::
		          distinct()
		          ->select('merchant.*','identity_merchant.identity_name as merchant_name','identity_merchant.identity_logo as merchant_logo','identity_merchant.identity_logo_compact as merchant_logo_compact','identity_merchant.identity_email as merchant_email','identity_merchant.identity_website as merchant_website')
		          ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
		          ->where('merchant.merchant_id',$merchantId)
		          ->where('identity_merchant.identity_name', 'LIKE', '%' . $searchMerchant . '%' )
		          ->paginate(25)->setPath('');
		  }

		  $pagination = $hase_merchants->appends(array(
		      'search_merchant' => $searchMerchant
		  ));
		  $permissions = PermissionTrait::getPermission('Hase_merchant');
		  
		  if (count($hase_merchants) > 0) {
		      return view('hase_merchant.index', compact('hase_merchants','title','permissions','merchant_parent_types','merchantType','searchMerchant'))->withDetails($hase_merchants)->withQuery($searchMerchant);
		  }
		  return view('hase_merchant.index', compact('hase_merchants','permissions','merchant_parent_types','merchantType','searchMerchant'))->withMessage('No Details found. Try to search again !');
		} else {
		  return redirect('hase_merchant');
		}
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return  \Illuminate\Http\Response
	 */
	public function create()
	{
		if($this->permissionDetails('Hase_merchant','add')){

			$title = 'Create - hase_merchant';

			$merchantType = session()->has('merchantType') ? session()->get('merchantType') :"";

			$merchant_parent_types = Merchant_type::all()->where('merchant_parent_id',0);

			return view('hase_merchant.create',compact('title','merchant_parent_types','merchantType'));
		}else{
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
		if($this->roleId == 1){
			$hase_merchant = new Merchant();
			$hase_identity = new identity_merchant();
		}else{
			$hase_merchant = new Merchant_stage();
			$hase_identity = new identity_merchant_stage();

			$hase_merchant->staff_id = $this->staffId;
			$hase_identity->staff_id = $this->staffId;
		}

		$merchantCode = substr(trim($request->merchant_name),0,4);
        $codeName = $this->generateCode($merchantCode,MERCHANT_CODE_SUFFIX);

        $hase_identity->identity_code = strtolower($codeName);
		$hase_identity->identity_name = $request->merchant_name;
		$hase_identity->identity_email = $request->merchant_email;
		$hase_identity->identity_website = $request->merchant_website;
		$hase_identity->identity_type_id = MERCHANT_IDENTITY_TYPE_ID;
		$hase_identity->identity_table_id = MERCHANT_TABLE_ID;

		$hase_merchant->merchant_status = isset($request->merchant_status) ? 1 : 0;

		($this->merchantId == 0) ? $hase_merchant->merchant_type_id = $request->merchant_type_id:"";

		$merchantDirName = md5($request->merchant_name);

		if($request->live_image_url)
        {
            $hase_identity->identity_logo = $request->live_image_url;
        } else {
    		if($request->file('merchant_logo')){

				$publicDirPath = public_path(env('image_dir_path'));
				
				if($this->roleId == 1){
					$imageDirPath = "merchant/$merchantDirName/";
				}else{
					$imageDirPath = "merchant_stage/$merchantDirName/";
				}

				$absoluteImageDirPath = $publicDirPath.$imageDirPath;

				if(!file_exists($absoluteImageDirPath)){
					mkdir($absoluteImageDirPath,0777,true);
				}

				$imageName = $request->file('merchant_logo')->getClientOriginalName();
                $imageArray = explode('.', $imageName);
                $hashImageName = md5($request->merchant_name.$imageName).".".$imageArray[1];
                $request->file('merchant_logo')->move($absoluteImageDirPath,$hashImageName);
                $hase_identity->identity_logo = "$imageDirPath$hashImageName";
			}
        }

        if($request->live_image_compact_url)
        {
            $hase_identity->identity_logo_compact = $request->live_image_compact_url;
        } else {
    		if($request->file('merchant_logo_compact')){

				$publicDirPath = public_path(env('image_dir_path'));
				
				if($this->roleId == 1){
					$imageDirPath = "merchant/$merchantDirName/";
				}else{
					$imageDirPath = "merchant_stage/$merchantDirName/";
				}

				$absoluteImageDirPath = $publicDirPath.$imageDirPath;

				if(!file_exists($absoluteImageDirPath)){
					mkdir($absoluteImageDirPath,0777,true);
				}

				$imageName = $request->file('merchant_logo_compact')->getClientOriginalName();
                $imageArray = explode('.', $imageName);
                $hashImageName = md5($request->merchant_name.$imageName).".".$imageArray[1];
                $request->file('merchant_logo_compact')->move($absoluteImageDirPath,$hashImageName);
                $hase_identity->identity_logo_compact = "$imageDirPath$hashImageName";				
			}
        }

        $hase_identity->save();
        $identityID = $hase_identity->identity_id;

        $hase_merchant->identity_table_id = MERCHANT_IDENTITY_TABLE_ID;
        $hase_merchant->identity_id = $identityID;

        $hase_merchant->save();
		$merchantID = $hase_merchant->merchant_id;
		

		// Add New Merchant Entry Into Stage Table If User is Not Admin
		if($this->roleId == 1){

			$approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

            $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

            $updatedIdentityColumns = array('identity_name','identity_email','identity_website','identity_logo','identity_logo_compact');

            $this->addAdminForApprove($identityID,$updatedIdentityColumns,$this->identity_request_table_live,$approvalStatus,$approvalCrudStatus,$merchantID,null);

            $updatedMerchantColumns = array('merchant_status');

            $this->addAdminForApprove($merchantID,$updatedMerchantColumns,$this->request_table_live,$approvalStatus,$approvalCrudStatus,$merchantID,null);

			$staffUrl = "/hase_staff/".$this->staffId."/edit";
			$merchantUrl = "/hase_merchant/".$merchantID."/edit";
			$action="added";
			$message = "<a href='".URL::to($staffUrl)."'>".$this->staffName."</a> <strong>added</strong> merchant <a href='".URL::to($merchantUrl)."'> <strong>".$hase_merchant->merchant_name."</strong></a>";
			PermissionTrait::addActivityLog($action,$message);

		}else{

			$approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

			$approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

			$updatedIdentityColumns = array('identity_name','identity_email','identity_website','identity_logo','identity_logo_compact');

            $this->addForApprove($identityID,$updatedIdentityColumns,$this->identity_request_table_live,$this->identity_request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantID,null);

			$updatedMerchantColumns = array('merchant_status');
			
			$parentApprovalData = $this->addForApprove($merchantID,$updatedMerchantColumns,$this->request_table_live,$this->request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantID,null);
			
			$staffUrl = "/hase_staff/".$this->staffId."/edit";
			$action="added";
			$message = "<a href='".URL::to($staffUrl)."'>".$this->staffName."</a> <strong> updated </strong> new merchant <strong> $hase_merchant->merchant_name </strong>";
			
			PermissionTrait::addActivityLog($action,$message);
		}

		Session::flash('type', 'success'); 
		Session::flash('msg', 'Merchant Successfully Created'); 

		if($this->roleId == 1){
			if ($request->submitBtn === "Save") {
				return redirect('hase_merchant/'. $merchantID . '/edit');
			}else{
				return redirect('hase_merchant');
			}
		}else{
			return redirect('hase_merchant');
		}
	}

	
	public function generateCode($orignalCodeName,$merchantSuffix)
    {
    	
    	if($this->codeCounter == 0){
    		$codeName = $orignalCodeName.$merchantSuffix;
    	}else{
    		$codeName = $orignalCodeName.$this->codeCounter.$merchantSuffix;
    	}

        $code_exist = identity_merchant::select('*')
                         ->where('identity_code',$codeName)
                         ->get()->first();

	    if(!count($code_exist)){
	        
	        return $codeName;

	    }else{
	     	$this->codeCounter = $this->codeCounter + 1;
	        return $this->generateCode($orignalCodeName,$merchantSuffix);
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
		$title = 'Show - hase_merchant';

		if($request->ajax())
		{
			return URL::to('hase_merchant/'.$id);
		}
		
		$hase_merchant = Merchant::findOrfail($id);
		return view('hase_merchant.show',compact('title','hase_merchant'));
	}

	/**
	 * Show the form for editing the specified resource.
	 * @param    \Illuminate\Http\Request  $request
	 * @param    int  $id
	 * @return  \Illuminate\Http\Response
	 */
	public function edit($id,Request $request)
	{
		if($this->permissionDetails('Hase_merchant','manage')){
			$title = 'Edit - hase_merchant';
			
			$merchant_parent_types = Merchant_type::all()->where('merchant_parent_id',0);

			$hase_merchant = Merchant::
									select('merchant.*','identity_merchant.identity_name as merchant_name','identity_merchant.identity_code as merchant_code','identity_merchant.identity_logo as merchant_logo','identity_merchant.identity_logo_compact as merchant_logo_compact','identity_merchant.identity_email as merchant_email','identity_merchant.identity_website as merchant_website')
									->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
									->where('merchant.merchant_id',$id)
									->get()->first();


			return view('hase_merchant.edit',compact('title','hase_merchant','merchant_parent_types'));
		}else{
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
		$hase_merchant = Merchant::findOrfail($id);

		$hase_identity = identity_merchant::findOrfail($hase_merchant->identity_id);

		if($request->merchant_code !==""){
            $request->merchant_code = strtolower($request->merchant_code);
        
	        $code_exist = identity_merchant::select('*')
	                         ->where('identity_id','!=',$request->identity_id)
	                         ->where('identity_code',$request->merchant_code)
	                         ->get()->first();


	        if(count($code_exist) == 0){
	            $hase_identity->identity_code = $request->merchant_code;
	        }   

        }


		$hase_identity->identity_name = $request->merchant_name;

		$hase_identity->identity_email = $request->merchant_email;
		
		$hase_identity->identity_website = $request->merchant_website;

		$hase_merchant->merchant_status = isset($request->merchant_status) ? 1 : 0;

		($this->merchantId == 0) ? $hase_merchant->merchant_type_id = $request->merchant_type_id:"";

		$merchantDirName = md5($hase_merchant->merchant_name);

		if($request->live_image_url)
        {
            $hase_merchant->merchant_logo = $request->live_image_url;
        } else {
    		if($request->file('merchant_logo')){

				$publicDirPath = public_path(env('image_dir_path'));
				
				if($this->roleId == 1){
					$imageDirPath = "merchant/$merchantDirName/";
				}else{
					$imageDirPath = "merchant_stage/$merchantDirName/";
				}

				$absoluteImageDirPath = $publicDirPath.$imageDirPath;

				if(!file_exists($absoluteImageDirPath)){
					mkdir($absoluteImageDirPath,0777,true);
				}

				if($this->roleId == 1){
					$imagePath = $publicDirPath.$hase_merchant->merchant_logo;
					if (is_file($imagePath)) {
						unlink($imagePath);
					}
				}

				$imageName = $request->file('merchant_logo')->getClientOriginalName();
                $imageArray = explode('.', $imageName);
                $hashImageName = md5($hase_merchant->merchant_name.$imageName).".".$imageArray[1];
                $request->file('merchant_logo')->move($absoluteImageDirPath,$hashImageName);
                $hase_identity->identity_logo = "$imageDirPath$hashImageName";
			}
        }

        if($request->live_image_compact_url)
        {
            $hase_merchant->merchant_logo_compact = $request->live_image_compact_url;
        } else {
    		if($request->file('merchant_logo_compact')){

				$publicDirPath = public_path(env('image_dir_path'));
				
				if($this->roleId == 1){
					$imageDirPath = "merchant/$merchantDirName/";
				}else{
					$imageDirPath = "merchant_stage/$merchantDirName/";
				}

				$absoluteImageDirPath = $publicDirPath.$imageDirPath;

				if(!file_exists($absoluteImageDirPath)){
					mkdir($absoluteImageDirPath,0777,true);
				}

				if($this->roleId == 1){
					$imagePath = $publicDirPath.$hase_merchant->merchant_logo_compact;
					if (is_file($imagePath)) {
						unlink($imagePath);
					}
				}

				$imageName = $request->file('merchant_logo_compact')->getClientOriginalName();
                $imageArray = explode('.', $imageName);
                $hashImageName = md5($hase_merchant->merchant_name.$imageName).".".$imageArray[1];
                $request->file('merchant_logo_compact')->move($absoluteImageDirPath,$hashImageName);
                $hase_identity->identity_logo_compact = "$imageDirPath$hashImageName";				
			}
        }

		$hase_merchant->merchant_status = isset($request->merchant_status) ? 1 : 0;   
		$merchantID = $hase_merchant->merchant_id;

		if($this->roleId == 1)
		{
			$merchantDirty = $hase_merchant->getDirty();
			$identityDirty = $hase_identity->getDirty();

			if($identityDirty){

				$updatedIdentityColumns = array();
                foreach ($identityDirty as $field => $newdata)
                {
                    $olddata = $hase_identity->getOriginal($field);
					if ($olddata !== $newdata)
					{
						$updatedIdentityColumns[] =  $field;
					}
                }
                if(count($updatedIdentityColumns));
                {
                    $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                    $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("modify");

                    $this->updateAdminForApprove($hase_merchant->identity_id,$updatedIdentityColumns,$this->identity_request_table_live,$approvalStatus,$approvalCrudStatus,$merchantID,null);
                }

                $hase_identity->save();
			}

            if($merchantDirty)
            {
                $updatedMerchantColumns = array();
                foreach ($merchantDirty as $field => $newdata)
                {
                    $olddata = $hase_merchant->getOriginal($field);
					if ($olddata !== $newdata)
					{
						$updatedMerchantColumns[] =  $field;
					}
                }
                if(count($updatedMerchantColumns));
                {
                    $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                    $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("modify");

                    $this->updateAdminForApprove($id,$updatedMerchantColumns,$this->request_table_live,$approvalStatus,$approvalCrudStatus,$merchantID,null);
                }

                $hase_merchant->save();

				$staffUrl = "/hase_staff/".$this->staffId."/edit";
				$merchantUrl = "/hase_merchant/".$merchantID."/edit";
				$action="updated";
				$message = "<a href='".URL::to($staffUrl)."'>".$this->staffName."</a> <strong>updated</strong> merchant <a href='".URL::to($merchantUrl)."'> <strong>".$hase_merchant->merchant_name."</strong></a>";
				PermissionTrait::addActivityLog($action,$message);
            }

		} else {

			$merchant_stage_data = array();
			$identity_stage_data = array();
			$hase_merchant_stage = new Merchant_stage();
			$hase_identity_stage = new identity_merchant_stage();
			$merchantDirty = $hase_merchant->getDirty();
			$identityDirty = $hase_identity->getDirty();

			if($identityDirty)
			{
				$hase_identity_stage->staff_id = $this->staffId;
				foreach ($identityDirty as $field => $newdata)
				{
				  $olddata = $hase_identity->getOriginal($field);
				  if ($olddata !== $newdata)
				  {
					$hase_identity_stage->$field = $newdata;
					$updatedIdentityColumns[] =  $field;
				  }
				}
				$hase_identity_stage->save();

				$approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

				$approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("modify");

				$this->updateForApprove($hase_merchant->identity_id,$hase_identity_stage->identity_id,$updatedIdentityColumns,$this->identity_request_table_live,$this->identity_request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantID,null);
			}

			if($merchantDirty)
			{
				$hase_merchant_stage->staff_id = $this->staffId;
				foreach ($merchantDirty as $field => $newdata)
				{
				  $olddata = $hase_merchant->getOriginal($field);
				  if ($olddata !== $newdata)
				  {
					$hase_merchant_stage->$field = $newdata;
					$updatedMerchantColumns[] =  $field;
				  }
				}
				$hase_merchant_stage->save();

				$approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

				$approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("modify");

				$this->updateForApprove($id,$hase_merchant_stage->merchant_id,$updatedMerchantColumns,$this->request_table_live,$this->request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantID,null);
			}

			$staffUrl = "/hase_staff/".$this->staffId."/edit";
			$action="updated";
			$message = "<a href='".URL::to($staffUrl)."'>".$this->staffName."</a> <strong> updated </strong> new merchant <strong> $hase_merchant->merchant_name </strong>";
			
			PermissionTrait::addActivityLog($action,$message);

		}

		Session::flash('type', 'success'); 
		Session::flash('msg', 'Merchant Successfully Updated');
		
		if($this->roleId == 1){
			if ($request->submitBtn === "Save") {
				return redirect('hase_merchant/'. $hase_merchant->merchant_id . '/edit');
			}else{
				return redirect('hase_merchant');
			}
		}else{
			return redirect('hase_merchant');
		}
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
		$msg = Ajaxis::BtDeleting('Warning!!','Would you like to remove This?','/hase_merchant/'. $id . '/delete');

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
		if($this->permissionDetails('Hase_merchant','delete')){
			$hase_merchant = Merchant::findOrfail($id);
			$hase_merchant->merchant_status = 0;
            $hase_merchant->save();
			/*$hase_merchant->delete();*/
			Session::flash('type', 'error'); 
			Session::flash('msg', 'Merchant Successfully Deleted');

			return redirect('hase_merchant');
		}else{
			return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
		}
	}

	public function getFilter(Request $request) {
        $merchantType = $request->merchant_type;
        session(['merchantType' => $merchantType]);
        return;
    }
}
