@extends('layouts/default')
{{-- Page title --}}
@section('title')
	Trade Order Type
	@parent
@stop
{{-- page level styles --}}
@section('header_styles')
@stop 


<!-- <link rel="stylesheet" type="text/css" href="{{asset('assets/kendoui/styles/kendo.common.min.css')}}"> -->
<link rel="stylesheet" href="https://kendo.cdn.telerik.com/2018.1.221/styles/kendo.common.min.css">
<link rel="stylesheet" href="https://kendo.cdn.telerik.com/2018.1.221/styles/kendo.rtl.min.css">
  <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2018.1.221/styles/kendo.default.min.css">
  <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2018.1.221/styles/kendo.mobile.all.min.css">
    <link rel="stylesheet" href="http://kendo.cdn.telerik.com/2018.1.221/styles/kendo.blueopal.min.css" />

<!-- <link rel="stylesheet" type="text/css" href="{{asset('assets/kendoui/styles/kendo.blueopal.min.css')}}"> -->

<style type="text/css">
	#tradeOrderTypeListForm label{
		font-weight: bold;
	}
	#tradeOrderTypeListForm div.form-group{
		background-color: white;
	    padding: 15px 10px;
	}
</style>

@section('content')
<section class="content-header">
	<h1>Order Type</h1>
	  <ol class="breadcrumb">
		  <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
		  <li><a href="#"> Order Type</a></li>
	  </ol>
</section>
<section class="content">
	<section class="content p-l-r-15">
		<div class="row">
			<div class="panel panel-primary ">
				<div class="panel-heading">
					<h4 class="panel-title">    
						<i class="fa fa-fw fa-users"></i> Order Type
					</h4>
				</div>
				<div style="background-color: #CEF2EF">
					<div class="panel-body">
						
						<form method='POST' action='{!!url("trade_order_type_list")!!}' id="tradeOrderTypeListForm">
							{{ csrf_field() }}
							<!-- <div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="topologyTree">Topology</label>
										<div id="topologyTree"></div>
										<span id="treeError" style="color: red;display: none;">Please select at least one Node</span>
									</div>
								</div>
							</div> -->
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="merchant_id">Merchants</label><br>
										<input type="text" id="merchant_id" name="merchant_id" style="width: 100%" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="type_id">Order Type</label>
										<select id="type_id" name="type_id[]"></select>
										<!-- <span data-for="type_id" class="k-invalid-msg"></span> -->
									</div>
								</div>
								<!-- <div class="col-md-4">
									<div class="form-group">
										<label for="customer_id">Customer</label>
										<input type="text" id="customer_id" name="customer_id" />
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="customer_account_id">Customer Account</label>
										<input type="text" id="customer_account_id" name="customer_account_id" />
									</div>
								</div> -->
							</div>
							<!-- <div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="staff_account_id">Staff Account</label>
										<input type="text" id="staff_account_id" name="staff_account_id" />
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="asset_id">Asset</label>
										<input type="text" id="asset_id" name="asset_id" />
									</div>
								</div>
							</div> -->
							<div class="row">
								<div class="col-md-1"></div>
								<div class="col-md-2">
									<button type="button" id="submitBtn" class="send-btn k-button">EDIT</button>
								</div>
								<div class="col-md-5"></div>

							</div>
							<!-- <input type="hidden" name="topologyTreeResultId" id="topologyTreeResultId" value="" /> -->
						</form>

						<div class="row">
							<div class="col-md-12">
								<div id="orderTypeListGrid"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</section>
@endsection
<script src="{{asset('assets/kendoui/js/jquery.min.js')}}" type="text/javascript"></script>

@section('footer_scripts')
<!-- <script src="{{asset('assets/kendoui/js/kendo.all.min.js')}}" type="text/javascript"></script> -->
<script src="https://kendo.cdn.telerik.com/2018.1.221/js/kendo.all.min.js"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/TradeOrderTypeListIndex.js')}}"></script>
@stop