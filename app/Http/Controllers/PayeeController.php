<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Payee;
use App\Postal;
use App\Identity_payee;
use App\Identity_payee_list;
use App\Merchant;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;

use URL;
use Auth;
use Session;
use DB;
use Redirect;

/**
 * Class PayeeController.
 *
 */
class PayeeController extends PermissionsController
{
	use PermissionTrait;

	public function __construct()
	{
		parent::__construct();
		
		$connectionStatus = ConnectionManager::setDbConfig('Payee');

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
		if($this->permissionDetails('Payee','access')) {
			$permissions = $this->getPermission("Payee");
			$payees = Payee::all();
			return view('payee.index',compact('payees','permissions'));
		} else {
		 return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
		}
	}

	public function payee_list()
	{
		return view('payee.payee');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return  \Illuminate\Http\Response
	 */
	public function create()
	{
		if($this->permissionDetails('Payee','add')) {
			$allPostal = Postal::
				select('postal.*','identity_postal.identity_name as postal_name')
				->join('identity_postal','identity_postal.identity_id','=','postal.identity_id')
				->where('postal.postal_id','!=',0)
				->orderBy('postal.postal_id')
				->get();
			return view('payee.create',compact('allPostal'));
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
		$identityTableId = PermissionTrait::getIdentityTableId("identity_payee");
		$payeeTableId = PermissionTrait::getIdentityTableId("payee");
		$identityTypeId = PermissionTrait::getIdentityTypeId("payee");

		$payee_exist = Identity_payee::
			where('identity_code',$request->payee_code)
			->get()->first();

		if($payee_exist){
			$identity_payee = Identity_payee::findOrfail($payee_exist->identity_id);
			$payeeInfo          = Payee::where('identity_id',$identity_payee->identity_id)->first();
			$payee          = Payee::findOrfail($payeeInfo->payee_id);
		}else{
			$identity_payee = new Identity_payee();
			$payee = new Payee();
		}

		$identity_payee->identity_code = $request->payee_code;
		$identity_payee->identity_name = $request->payee_name;
		$identity_payee->identity_table_id = $identityTableId;
		$identity_payee->identity_type_id = $identityTypeId;
		$identity_payee->save();
		

		$payee->identity_id = $identity_payee->identity_id;
		$payee->identity_table_id = $payeeTableId;
		$payee->save();

		if(!$payee_exist){
			$identity_payee_list = new Identity_payee_list;
			
			if($this->merchantId == 0) {
				$identity_payee_list->payee_id = $payee->payee_id;
				$identity_payee_list->save();
			}else{
				$merchantInfo = Merchant::
					select('identity_merchant.*')
					->join('identity_merchant','merchant.identity_id','identity_merchant.identity_id')
					->where('merchant_id',$this->merchantId)
					->get()->first();
				
				$identity_payee_list->identity_id = $merchantInfo->identity_id;
				$identity_payee_list->identity_table_id = $merchantInfo->identity_table_id;
				$identity_payee_list->payee_id = $payee->payee_id;
				$identity_payee_list->save();
			}
		}
		return 1;
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
		$title = 'Show - payee';

		if($request->ajax())
		{
			return URL::to('payee/'.$id);
		}

		$payee = Payee::findOrfail($id);
		return view('payee.show',compact('title','payee'));
	}

	/**
	 * Show the form for editing the specified resource.
	 * @param    \Illuminate\Http\Request  $request
	 * @param    int  $id
	 * @return  \Illuminate\Http\Response
	 */
	public function edit($id,Request $request)
	{
		if($this->permissionDetails('Payee','manage')) {
			$payee = Payee::findOrfail($id);
			return view('payee.edit',compact('payee'));
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
		$payee = Payee::findOrfail($id);
				
		$payee->identity_id = $request->identity_id;
		
		$payee->identity_table_id = $request->identity_table_id;
		
		$payee->save();
		Session::flash('type', 'success'); 
		Session::flash('msg', 'Payee Successfully Updated');

		if ($request->submitBtn === "Save") {
		   return redirect('payee/'. $payee->payee_id . '/edit');
		}else{
		   return redirect('payee');
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
		if($this->permissionDetails('Payee','delete')) {
			$payee = Payee::findOrfail($id);
			$payee->delete();
			Session::flash('type', 'success');
			Session::flash('msg', 'Payee Successfully Deleted');
			return redirect('payee');
		} else {
			return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
		}
	}

	public function getPayeesData(Request $request) {

		if($this->merchantId == 0){

			$payees = Payee::
			select(
				'payee.*',
				'identity_payee.identity_code as payee_code',
				'identity_payee.identity_name as payee_name'
			)
			->join('identity_payee','identity_payee.identity_id','payee.identity_id')
			->where('payee.payee_id','!=',0)
			->orderBy('payee.payee_id')
			->offset($request->skip)
			->limit($request->take)
			->get();

			$total_records = Payee::
				join('identity_payee','identity_payee.identity_id','payee.identity_id')
				->where('payee.payee_id','!=',0)
				->count();
			
		}else{
			$identity_merchant = Merchant::
				select('identity_merchant.*')
				->join('identity_merchant','merchant.identity_id','identity_merchant.identity_id')
				->where('merchant_id',$this->merchantId)
				->get()->first();

			$payees = Payee::
				select(
					'payee.*',
					'identity_payee.identity_code as payee_code',
					'identity_payee.identity_name as payee_name'
				)
				->join('identity_payee','identity_payee.identity_id','payee.identity_id')
				->join('identity_payee_list','identity_payee_list.payee_id','payee.payee_id')
				->where('payee.payee_id','!=',0)
				->where('identity_payee_list.identity_id',$identity_merchant->identity_id)
				->where('identity_payee_list.identity_table_id',$identity_merchant->identity_table_id)


				->orWhere(function ($query) {
					$query->where('identity_payee_list.identity_id',0)
						->where('identity_payee_list.identity_table_id',0);
				})

				->orderBy('payee.payee_id')
				->offset($request->skip)
				->limit($request->take)
				->get();

			$total_records = Payee::
				join('identity_payee','identity_payee.identity_id','payee.identity_id')
				->join('identity_payee_list','identity_payee_list.payee_id','payee.payee_id')
				->where('payee.payee_id','!=',0)
				->where('identity_payee_list.identity_id',$identity_merchant->identity_id)
				->where('identity_payee_list.identity_table_id',$identity_merchant->identity_table_id)
				->orWhere(function ($query) {
					$query->where('identity_payee_list.identity_id',0)
						->where('identity_payee_list.identity_table_id',0);
				})
				->count();	
		}

		$payees_data['payees'] = $payees;
		$payees_data['total'] = $total_records;

		return json_encode($payees_data);
	}

	public function updatePayee(Request $request) {
		$payeeId = $request->payee_id;
		$identityId = $request->identity_id;
		$key = $request->key;
		$value = $request->value;
		if($key === "identity_code") {
			$identityPayee = Identity_payee::findOrfail($identityId);
			$identityPayee->identity_code = $value;
			$identityPayee->save();
		}
		else if($key === "identity_name") {
			$identityPayee = Identity_payee::findOrfail($identityId);
			$identityPayee->identity_name = $value;
			$identityPayee->save();
		}
	}

	public function getIdentities(Request $request)
	{
		switch ($request->identity_table_id) {
			case 4:
				$customers = PermissionTrait::getCustomers();
				return json_encode($customers);
				break;
			case 8:
				$merchants = PermissionTrait::getMerchants();
				return json_encode($merchants);
				break;
			case 15:
				$peoples = PermissionTrait::getPeoples();
				return json_encode($peoples);
				break;
			default:
				return false;
				break;
		}

		$identityTable = PermissionTrait::getIdentityTable($request->identity_table_id);

		$identities = DB::table($identityTable->table_code)
						->where($identityTable->table_key,0)
						->get();
	}

	public function getPayeeList() {
		$payees = Identity_payee_list::
			select(
				'payee.*',
				'identity_payee.identity_code as payee_code',
				'identity_payee.identity_name as payee_name'
			)
			->join('payee','payee.payee_id','identity_payee_list.payee_id')
			->join('identity_payee','identity_payee.identity_id','payee.identity_id')
			->where('payee.payee_id','!=',0)
			->where('identity_payee_list.identity_table_id',0)
			->orderBy('payee.payee_id')
			->get();
		return json_encode($payees);
	}

	public function getIdentityPayeeList(Request $request)
	{   
		$originTable = PermissionTrait::getTableType($request->identity_table_id);
		$originTableInfo = PermissionTrait::getIdentityTableType($originTable->table_code,$request->identity_id);
		$identityTable = PermissionTrait::getTableType($originTableInfo->identity_table_id);

		$originTableName = $originTable->table_code;
		$identityTableName = $identityTable->table_code;

		$identity_payee_lists = Identity_payee_list::
			select(
				'identity_payee_list.*',

				$identityTableName.'.identity_name',
				$identityTableName.'.identity_code',
			
				'identity_payee.identity_code as payee_code',
				'identity_payee.identity_name as payee_name'
			)

			->join($originTableName,$originTableName.'.identity_id','identity_payee_list.identity_id')
			->join($identityTableName,'identity_payee_list.identity_id',$identityTableName.'.identity_id')

			->join('payee','payee.payee_id','identity_payee_list.payee_id')
			->join('identity_payee','identity_payee.identity_id','payee.identity_id')

			->where('identity_payee_list.identity_id',$request->identity_id)
			->where('identity_payee_list.identity_table_id',$request->identity_table_id)
			->get();
		return json_encode($identity_payee_lists);
	}

	public function createPayeeList(Request $request)
	{
		
		$payeeArray = array();
		$dataArray = array();

		foreach ($request->payee_id as $key => $payee) {
			$payeeArray[] = $payee;
		}

		Identity_payee_list::
				where("identity_id",$request->identity_id)
				->where("identity_table_id",$request->identity_table_id)
				->whereNotIn('payee_id',$payeeArray)
				->delete();

		foreach ($payeeArray as $key => $payeeId) {
			
			$payeeInfo = Identity_payee_list::
				where("identity_id",$request->identity_id)
				->where("identity_table_id",$request->identity_table_id)
				->where('payee_id',$payeeId)
				->get()->first();

			if(!$payeeInfo){
				$dataArray[$key] = array(
					'identity_id' => $request->identity_id,
					'identity_table_id' => $request->identity_table_id,
					'payee_id' => $payeeId
				);
			}
		}
		Identity_payee_list::insert($dataArray);
		return 1;
	}
}