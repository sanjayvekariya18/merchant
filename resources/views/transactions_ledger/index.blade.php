@extends('layouts/default')
{{-- Page title --}}
@section('title')
	Transaction Ledger
	@parent
@stop

{{-- page level styles --}}
@section('header_styles')
	<!--page level css -->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
	<!--end of page level css-->
@stop
@section('content')
<section class="content-header">
	<h1>Transactions Ledger</h1>
	<ol class="breadcrumb">
		<li>
			<a href="index ">
				<i class="fa fa-fw fa-home"></i> Dashboard
			</a>
		</li>
		<li class="active">
			Transactions Ledger
		</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<?php if(in_array("add", $permissions)): ?>
				<a href='{!!url("transactions_ledger")!!}/create' class='btn btn-primary btn-inline'>Create New Transaction Ledger</a>
			<?php endif; ?>
		</div>
	</div>
	<br>
	<section class="content p-l-r-15">
		<div class="row">
			<div class="panel panel-primary ">
				<div class="panel-heading">
					<h4 class="panel-title">
						<i class="fa fa-fw fa-square-o"></i> Transaction Ledger List
					</h4>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class = "table table-bordered" id="table">
							<thead>
								<?php if(in_array("manage", $permissions) || in_array("delete", $permissions)):?>
									<th>Actions</th>
								<?php endif; ?>
								<th>Id</th>
								<th>Merchant Name</th>
								<th>Location Name</th>
								<th>Staff Group</th>	
								<th>Trader Name</th>
								<th>Customer Code</th>
								<th>Account Name</th>
								<th>Exchange Name</th>
								<th>Trade Timezone</th>
								<th>Trade Date</th>
								<th>Trade Time</th>
								<th>Side Type</th>
								<th>Asset From</th>
								<th>Asset From Price</th>
								<th>Asset From Quantity</th>
								<th>Asset Into</th>
								<th>Asset Into Price</th>
								<th>Asset Into Quantity</th>
								<th>Order Type</th>
								<th>Status Type</th>
								<th>Reason Type</th>
								<th>Fee Amount</th>
								<th>Fee Asset</th>
								<th>Fee Referrer</th>
								<th>Transaction Address</th>
								<th>Transaction Address Url</th>
								<th>Transaction Type</th>
								<th>Transaction Exchange</th>
								<th>Transaction Internal</th>
							</thead>
							<tbody>
								@foreach($transactions_ledgers as $transactions_ledger) 
									<tr>
										<?php if(in_array("manage", $permissions) || in_array("delete", $permissions)):?>
											<td>
												<?php if(in_array("manage", $permissions)): ?>
													<a href = '{!!url("transactions_ledger")!!}/{!!$transactions_ledger->ledger_id!!}/edit'><i class = 'fa fa-fw fa-pencil text-primary actions_icon' title="Edit Table"></i></a>
												<?php endif; ?>

												<?php if(in_array("delete", $permissions)): ?>
													<a href='#' data-toggle="modal" data-target="#delete" data-link = '{!!url("transactions_ledger")!!}/{!!$transactions_ledger->ledger_id!!}/delete'><i class = 'fa fa-fw fa-times text-danger actions_icon' title="Delete Table"></i></a>
												<?php endif; ?>
											</td>
										<?php endif; ?>
	                                    <td>{!!$transactions_ledger->ledger_id!!}</td>
	                                    <td>{!!$transactions_ledger->merchant_name!!}</td>
	                                    <td>{!!$transactions_ledger->location_name!!}</td>
	                                    
	                                    <td>{!!$transactions_ledger->staff_group_name!!}</td>
	                                    <td>{!!$transactions_ledger->staff_name!!}</td>
	                                    <td>{!!$transactions_ledger->customer_code!!}</td>

										<td>{!!$transactions_ledger->account_code_long." (".$transactions_ledger->account_code_short.")"!!}</td>
										<td>{!!$transactions_ledger->exchange_name!!}</td>
										
										<td>{!!$transactions_ledger->timezone_name!!}</td>
										<td>{{($transactions_ledger->trade_date)?date('m-d-Y',strtotime($transactions_ledger->trade_date)):''}}</td>
										<td>{{($transactions_ledger->trade_time)?date('H:i',$transactions_ledger->trade_time):''}}</td>
										<td>{!!$transactions_ledger->side_type_name!!}</td>
										
										<td>{!!$transactions_ledger->asset_from_code!!}</td>
										<td>{!!$transactions_ledger->asset_from_price!!}</td>
										<td>{!!$transactions_ledger->asset_from_quantity!!}</td>

										<td>{!!$transactions_ledger->asset_into_code!!}</td>
										<td>{!!$transactions_ledger->asset_into_price!!}</td>
										<td>{!!$transactions_ledger->asset_into_quantity!!}</td>

										<td>{!!$transactions_ledger->trade_order_type_name!!}</td>
										<td>{!!$transactions_ledger->trade_status_name!!}</td>
										<td>{!!$transactions_ledger->trade_reason_type_name!!}</td>

										<td>{!!$transactions_ledger->fee_amount!!}</td>
										<td>{!!$transactions_ledger->fee_asset_code!!}</td>
										<td>{!!($transactions_ledger->fee_referrer_id)?$transactions_ledger->fee_referrer_id:"None"!!}</td>

										<td>{!!$transactions_ledger->transaction_address!!}</td>
										<td>{!!$transactions_ledger->transaction_address_url!!}</td>
										<td>{!!$transactions_ledger->trade_transaction_type_name!!}</td>
										<td>{!!$transactions_ledger->transaction_exchange!!}</td>
										<td>{!!$transactions_ledger->transaction_internal!!}</td>
									</tr>
								@endforeach 
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<!-- /.modal-dialog -->
			<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="Heading" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title custom_align" id="Heading">Delete Transaction Ledger</h4>
						</div>
						<div class="modal-body">
							<div class="alert alert-warning">
								<span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to
								delete this Transaction Ledger ?
							</div>
						</div>
						<div class="modal-footer ">
							<a href="#" class="btn btn-danger">
								<span class="glyphicon glyphicon-ok-sign"></span> Yes
							</a>
							<button type="button" class="btn btn-success" data-dismiss="modal">
								<span class="glyphicon glyphicon-remove"></span> No
							</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
			</div>
			<!-- /.modal-dialog -->
		</div>
	</section>
</section>
@endsection

{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->

<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/jquery.dataTables.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.bootstrap.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/users_custom.js')}}"></script>
<script type="text/javascript">
	@if(Session::has('type'))
	   toastr.options = {
	"closeButton": true,
	"positionClass": "toast-top-right",
	"showDuration": "1000",
	"hideDuration": "1000",
	"timeOut": "5000",
	"extendedTimeOut": "1000",
	"showEasing": "swing",
	"hideEasing": "swing",
	"showMethod": "show"
	};
	var $toast = toastr["{{ Session::pull('type') }}"]("", "{{ Session::pull('msg') }}");
	   @endif
</script>
<!-- end of page level js -->
@stop