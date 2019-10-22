@extends('layouts/default')
{{-- Page title --}}
@section('title')
	Transaction Summary
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
	<h1>Transaction Summary</h1>
	<ol class="breadcrumb">
		<li>
			<a href="index ">
				<i class="fa fa-fw fa-home"></i> Dashboard
			</a>
		</li>
		<li class="active">
			Transaction Summary
		</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<?php if(in_array("add", $permissions)): ?>
				<a href='{!!url("transaction_summary")!!}/create' class='btn btn-primary btn-inline'>Create New Transaction Summary</a>
			<?php endif; ?>
		</div>
	</div>
	<br>
	<section class="content p-l-r-15">
		<div class="row">
			<div class="panel panel-primary ">
				<div class="panel-heading">
					<h4 class="panel-title">
						<i class="fa fa-fw fa-square-o"></i> Transaction Summary List
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
								<th>Staff Name</th>
								<th>Staff Account Name</th>
								<th>Customer Code</th>
								<th>Customer Account Name</th>
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
								<th>Transaction Root</th>
							</thead>
							<tbody>
								@foreach($transaction_summaries as $transaction_summary) 
									<tr>
										<?php if(in_array("manage", $permissions) || in_array("delete", $permissions)):?>
											<td>
												<?php if(in_array("manage", $permissions)): ?>
													<a href = '{!!url("transaction_summary")!!}/{!!$transaction_summary->summary_id!!}/edit'><i class = 'fa fa-fw fa-pencil text-primary actions_icon' title="Edit Table"></i></a>
												<?php endif; ?>

												<?php if(in_array("delete", $permissions)): ?>
													<a href='#' data-toggle="modal" data-target="#delete" data-link = '{!!url("transaction_summary")!!}/{!!$transaction_summary->summary_id!!}/delete'><i class = 'fa fa-fw fa-times text-danger actions_icon' title="Delete Table"></i></a>
												<?php endif; ?>
											</td>
										<?php endif; ?>
	                                    <td>{!!$transaction_summary->summary_id!!}</td>
	                                    <td>{!!$transaction_summary->merchant_name!!}</td>
	                                    <td>{!!$transaction_summary->city_name!!}</td>
	                                    
	                                    <td>{!!$transaction_summary->staff_group_name!!}</td>
	                                    <td>{!!$transaction_summary->staff_name!!}</td>
	                                    <td>{!!$transaction_summary->staff_account_code_short!!}</td>
	                                    <td>{!!$transaction_summary->customer_code!!}</td>
										<td>{!!$transaction_summary->customer_account_code_short!!}</td>

										<td>{!!$transaction_summary->exchange_name!!}</td>
										
										<td>{!!$transaction_summary->timezone_name!!}</td>
										<td>{{($transaction_summary->trade_date)?date('m-d-Y',strtotime($transaction_summary->trade_date)):''}}</td>
										<td>{{($transaction_summary->trade_time)?date('H:i',$transaction_summary->trade_time):''}}</td>
										<td>{!!$transaction_summary->side_type_name!!}</td>
										
										<td>{!!$transaction_summary->asset_from_code!!}</td>
										<td>{!!$transaction_summary->asset_from_price!!}</td>
										<td>{!!$transaction_summary->asset_from_quantity!!}</td>

										<td>{!!$transaction_summary->asset_into_code!!}</td>
										<td>{!!$transaction_summary->asset_into_price!!}</td>
										<td>{!!$transaction_summary->asset_into_quantity!!}</td>

										<td>{!!$transaction_summary->type_name!!}</td>
										<td>{!!$transaction_summary->trade_status_name!!}</td>
										<td>{!!$transaction_summary->trade_reason_type_name!!}</td>

										<td>{!!$transaction_summary->fee_amount!!}</td>
										<td>{!!$transaction_summary->fee_asset_code!!}</td>
										<td>{!!($transaction_summary->fee_referrer_id)?$transaction_summary->fee_referrer_id:"None"!!}</td>

										<td>{!!$transaction_summary->transaction_address!!}</td>
										<td>{!!$transaction_summary->transaction_address_url!!}</td>
										<td>{!!$transaction_summary->trade_transaction_type_name!!}</td>
										<td>{!!$transaction_summary->transaction_exchange_id!!}</td>
										<td>{!!$transaction_summary->transaction_internal!!}</td>
										<td>{!!$transaction_summary->transaction_root!!}</td>
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
							<h4 class="modal-title custom_align" id="Heading">Delete Transaction Summary</h4>
						</div>
						<div class="modal-body">
							<div class="alert alert-warning">
								<span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to
								delete this Transaction Summary ?
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
		<!-- row-->
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