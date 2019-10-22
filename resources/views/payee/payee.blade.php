@extends('layouts/default')
{{-- Page title --}}
@section('title')
	Payee List
	@parent
@stop
{{-- page level styles --}}
@section('header_styles')
@stop 


<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.common.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.rtl.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.default.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.mobile.all.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.blueopal.min.css')}}" />

<style type="text/css">

	table thead tr{
        background-color: #d9ecf5;
        color: #003f59
    }
    table tr th,table tr td{
        width: 33%;
    } 
</style>

@section('content')
<section class="content-header">
	<h1>Payee List</h1>
	  <ol class="breadcrumb">
		  <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
		  <li><a href="#"> Payee List</a></li>
	  </ol>
</section>
<section class="content">
	<section class="content p-l-r-15">
		<div class="preloader" style="background: none !important; ">
			<div class="loader_img">
				<img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
			</div>
		</div>
		<div class="row">
			<div class="panel panel-primary ">
				<div class="panel-heading">
					<h4 class="panel-title">    
						<i class="fa fa-fw fa-users"></i> Payee List
					</h4>
				</div>
				<div class="panel-body">
					<form method='POST' action='{!!url("payee_list")!!}' id="identityPayeeListForm">
						{{ csrf_field() }}
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive">
									<table class = "table table-bordered">
										<thead>
											<tr>
												<th>Identity Type</th>
												<th>Identities</th>
												<th>Payee</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<input type="text" id="identity_table_id" name="identity_table_id" style="width: 100%" />
												</td>
												<td>
													<input type="text" id="identity_id" name="identity_id" style="width: 100%" />
												</td>
												<td>
													<select id="payee_id" name="payee_id[]" style="width: 100%"></select>
												</td>
											</tr>
										</tbody>
									</table>
									<table class = "table table-bordered">
										<thead>
											<tr>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<tr style="text-align: center;">
												<td>
													<button type="button" id="submitBtn" class="send-btn k-button">Add Payee</button>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</form>
					<div class="row">
						<div class="col-md-12">
							<div id="identityPayeeListGrid"></div>
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
<script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/PayeeListIndex.js')}}"></script>
@stop