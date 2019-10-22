@extends('layouts/default')
{{-- Page title --}}
@section('title')
	Trade Order Side
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
	#tradeOrderSideListForm label{
		font-weight: bold;
	}
	#tradeOrderSideListForm div.form-group{
		background-color: white;
	    padding: 15px 10px;
	}
</style>

@section('content')
<section class="content-header">
	<h1>Order Side</h1>
	  <ol class="breadcrumb">
		  <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
		  <li><a href="#"> Order Side</a></li>
	  </ol>
</section>
<section class="content">
	<section class="content p-l-r-15">
		<div class="row">
			<div class="panel panel-primary ">
				<div class="panel-heading">
					<h4 class="panel-title">    
						<i class="fa fa-fw fa-users"></i> Order Side
					</h4>
				</div>
				<div style="background-color: #CEF2EF">
					<div class="panel-body">
						
						<form method='POST' action='{!!url("trade_order_side_list")!!}' id="tradeOrderSideListForm">
							{{ csrf_field() }}
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="merchant_id">Merchants</label><br>
										<input type="text" id="merchant_id" name="merchant_id" style="width: 100%" />
									</div>
								</div>
							</div>
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
										<label for="side_type_id">Order Side</label>
										<select id="side_type_id" name="side_type_id[]"></select>
									</div>
								</div>
							</div>
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
								<div id="orderSideListGrid"></div>
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
<script type="text/javascript" src="{{asset('assets/js/custom_js/TradeOrderSideListIndex.js')}}"></script>
@stop