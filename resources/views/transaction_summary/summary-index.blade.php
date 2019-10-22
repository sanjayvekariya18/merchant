@extends('layouts/default')

{{-- Page title --}}
@section('title')
	Transaction Summary
	@parent
@stop

{{-- page level styles --}}
@section('header_styles')
<!--page level css -->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/kendoui/styles/kendo.common.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/kendoui/styles/kendo.uniform.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/kendoui/styles/kendo.mobile.all.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/kendoui/styles/kendo.blueopal.min.css')}}">
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
	<section class="content p-l-r-15">
		<div class="row">
			<div class="panel panel-primary ">
				<div class="panel-heading">
					<h4 class="panel-title">    
						<i class="fa fa-fw fa-users"></i> Transaction Summary
					</h4>
				</div>
				<input type="hidden" id="requestUrl" value="{!!url('transaction_summary')!!}">
				<div class="panel-body">
					<div id="transactionSummaryGrid"></div>
				</div>
			</div>
		</div>
 	</section>
</section>
@endsection
{{-- page level scripts --}}
<script src="{{asset('assets/kendoui/js/jquery.min.js')}}" type="text/javascript"></script>
@section('footer_scripts')
<script src="{{asset('assets/kendoui/js/kendo.all.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/TransactionSummary.js')}}"></script>
@stop