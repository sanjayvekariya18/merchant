@extends('layouts/default')
{{-- Page title --}}
@section('title')
Account List
@parent
@stop
{{-- page level styles --}}
@section('header_styles')
	<style>
		table thead tr{
			background-color: #d9ecf5;
			color: #003f59
		}
		table tr th,table tr td{
			width: 33%;
		}
		span.k-error{
			color: red;
		}
		.k-alert{
			top:250px !important;
			min-width: 200px !important;
		}
	</style>
	<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.common.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.rtl.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.default.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.mobile.all.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.blueopal.min.css')}}">	
@stop

@section('content')
	<section class="content-header">
		<h1>Account List</h1>
		<ol class="breadcrumb">
			<li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
			<li><a href="active"> Account List</a></li>
		</ol>
	</section>
	<section class="content">
		<section class="content p-l-r-15">
			<div class="row">
				<div class="panel panel-primary ">
					<div class="panel-heading">
						<h4 class="panel-title">    
							<i class="fa fa-fw fa-users"></i> Account List
						</h4>
					</div>
					<input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
					<div class="panel-body" style="background-color: #d9ecf5">
						<div class="row">
							<div class="col-md-12">
								<div id="tabstrip">
									<ul>
										<li class="k-state-active">Customer</li>
										<li>Merchant</li>
									</ul>
									<div id="tab1">
										<form method='POST' action='{!!url("account")!!}' id="customerAccountListForm">
											{{ csrf_field() }}

											<div class="table-responsive">
												<table class = "table table-bordered">
													<thead>
														<tr>
															<th>Merchant Type</th>
															<th>Merchants</th>
															<th>Customers</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>
																<input id="c_merchant_type_id" name="merchant_type_id" />
															</td>
															<td>
																<input id="c_merchant_id" name="merchant_id" disabled="true" />
															</td>
															<td>
																<input id="c_customer_id" name="customer_id" disabled="true" />
															</td>
														</tr>
													</tbody>
												</table>
												<table class = "table table-bordered" style = 'background:#fff'>
													<thead>
														<tr>
															<th>Filter</th>
															<th>Assets</th>
															<th>Settlement</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>
																<input id="c_filter" value="0" disabled="true" />
															</td>
															<td>
																<select id="c_asset_id" name="asset_id[]" disabled="true"></select>
															</td>
															<td>
																<input id="c_settlement_id" name="account_settlement" />
															</td>
														</tr>
													</tbody>
												</table>
												<table class = "table table-bordered" style = 'background:#fff;'>
													<thead>
														<tr>
															<th>Referrer</th>
															<th>Referrer Fee</th>
															<th>Trading Fee</th>
															
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>
																<input id="c_referrer_id" name="referrer_id" disabled="true" />
															</td>
															<td>
																<input id="c_referrer_fee" name="referrer_fee" disabled="true" />
															</td>
															<td>
																<input id="c_fee_percentage" name="fee_percentage" />
															</td>
														</tr>
													</tbody>
												</table>
												<table class = "table table-bordered" style = 'background:#fff;'>
													<thead>
														<tr>
															<th>Credit</th>
															<th></th>
															<th></th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>
																<input id="c_credit" name="credit" value="0" />
															</td>
															<td>
																<button type="button" id="c_submitBtn" class="send-btn k-button">Create Account</button>
															</td>
															<td></td>
														</tr>
													</tbody>
												</table>
											</div>
											<div id="customerAccountListGrid"></div>
										</form>
									</div>
									<div id="tab2">
										<form method='POST' action='{!!url("account")!!}' id="merchantAccountListForm">
											{{ csrf_field() }}
											<table class = "table table-bordered" style = 'background:#fff'>
												<thead>
													<tr>
														<th>Merchant Type</th>
														<th>Merchants</th>
														<th>Assets</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														@if(!Session::get('merchantId'))
															<td>
																<input id="merchant_type_id" name="merchant_type_id" />
															</td>
														@endif
														<td>
															<input id="merchant_id" name="merchant_id" disabled="true" />
														</td>
														<td>
															<select id="asset_id" name="asset_id[]" disabled="true"></select>
														</td>
													</tr>
												</tbody>
											</table>
											<table class = "table table-bordered" style = 'background:#fff;'>
												<thead>
													<tr>
														<th>Settlement</th>
														<th>Credit</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>
															<input id="settlement_id" name="account_settlement" />
														</td>
														<td>
															<input id="credit" name="credit" value="0" />
														</td>
														<td>
															<button type="button" id="submitBtn" class="send-btn k-button">Create Account</button>
														</td>
													</tr>
												</tbody>
											</table>
											<div id="merchantAccountListGrid"></div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- form-modal -->
			<div id="top_modal" class="modal fade animated position_modal" role="dialog">
				<div class="modal-dialog" style="width: 76% !important">
					<div class="modal-content" style="background-color: #d9ecf5">
						<div class="modal-header" style="background-color: #13688c;color: white">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">abc-merc USD</h4>
						</div>

						<div class="panel panel-primary" style="border-color: snow;">
							<div class="panel-body" >
								<!-- <div class="preloader" style="background: none !important; ">
									<div class="loader_img">
										<img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
									</div>
								</div> -->
								<form method='POST' action="{{url('account/createAccountWallet')}}" id="walletForm">
									{{ csrf_field() }}
									<table class = "table table-bordered" style = 'background:#fff;'>
										<thead>
											<tr>
												<th style="width: 30%">Wallet Name</th>
												<th style="width: 70%">Wallet Address</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<input type="text" class="k-textbox" id="wallet_name" name="wallet_name" placeholder="Wallet Name" style="width: 100%" required validationMessage="Name Required" />
												</td>
												<td>
													<input type="text" class="k-textbox" id="wallet_address" name="wallet_address" placeholder="Wallet Address" style="width: 100%" required validationMessage="Address Required"/>
													<span class="k-error"></span>
												</td>
											</tr>
										</tbody>
									</table>
									<table class = "table table-bordered" style = 'background:#fff;'>
										<thead>
											<tr>
												<th></th>
												
											</tr>
										</thead>
										<tbody>
											<tr style="text-align: center">
												<td>
													<button type="submit" id="w_submitBtn" class="send-btn k-button">Create Wallet</button>
												</td>
											</tr>
										</tbody>
									</table>
									<input type="hidden" id="account_id" name="account_id" value="" />
									<input type="hidden" id="wallet_asset_id" name="wallet_asset_id" value="" />
								</form>
								<div id="AccountWalletListGrid"></div>
							</div>
						</div>
						<!-- <div class="row" style="text-align: center;">
							<div class="col-md-12">
								<button type="button" class="btn btn-danger" data-dismiss="modal">
									Close
								</button>
							</div>
						</div> -->
					</div>
				</div>
			</div>
			<!-- form-modal end -->
		</section>
	</section>
@endsection
<script src="{{asset('assets/kendoui/js/jquery.min.js')}}" type="text/javascript"></script>
@section('footer_scripts')
	<script src="{{asset('assets/kendoui/js/kendo.all.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('assets/js/custom_js/AccountMerchant.js')}}"></script>
	<script type="text/javascript" src="{{asset('assets/js/custom_js/AccountCustomer.js')}}"></script>
	<script type="text/javascript" src="{{asset('assets/js/custom_js/AccountWallet.js')}}"></script>
@stop