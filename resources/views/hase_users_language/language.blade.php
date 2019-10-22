@extends('layouts/default')
{{-- Page title --}}
@section('title')
	Language
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
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
    <link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
<style type="text/css">

	#create_user_language_form label{
		font-weight: bold;
	}

	#create_user_language_form div.form-group{
		background-color: #d9ecf5;
		padding: 15px 10px;
	}

	.k-i-close{
		margin: 1px 0 !important;
	}

	#tabstrip div.row{
		margin: 10px 0px;
	}

	#locationInfo thead tr{
		width: 100%;
		background-color: #d9ecf5;
		color: #003f59
	}
</style>

@section('content')
<section class="content-header">
	<h1>Language</h1>
	  <ol class="breadcrumb">
		  <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
		  <li><a href="#"> Language Identities</a></li>
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
						<i class="fa fa-fw fa-users"></i> Language Identities
					</h4>
				</div>
				<div class="panel-body">
					<form method='POST' action='{!!url("users_language")!!}' id="create_user_language_form">
						{{ csrf_field() }}
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="identity_table_id">Identity Type</label><br>
									<input type="text" id="identity_table_id" name="identity_table_id" style="width: 100%" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="identity_id">Identites</label><br>
									<input type="text" id="identity_id" name="identity_id" style="width: 100%" />
								</div>
							</div>
						</div>			
					<div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">
                                            Select Language
                                        </h3>
                                    </div>
                                    <div class="panel-body" id="panelList">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <label class="col-md-5">Language</label>
                                                    <label class="col-md-2">Priority</label>
                                                </div>
                                                <div class="row" id="demo">
                                            <div class="col-sm-5">
                                               <select name="language_id" id="language_id" class ="form-control select21" required="true" >
                                                                        <option></option>
                                                                            @foreach($Users_language as $users_language)
                                                                            <option value="{{$users_language->language_id}}">{{$users_language->language_name}}</option>
                                                                            @endforeach
                                                                        </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <input id="language_priority" name="language_priority" type="number" class="form-control" min="0" value="0" required="true">
                                            </div>
                                            <div class="col-sm-5">
                                            <button type="button" id='addLanguage' value="addLanguage" class = 'btn btn-primary btn-inline'> + </button>
                                        </div> 
                                            <br><br>
                                            <br>
                                </div>
                                <div class="row" id="languageDetails" style="display: none;">
                                            <div class="col-sm-5">
                                               <select name="language_id1" id="language_id1" class ="form-control select22">
                                                                        <option></option>
                                                                            @foreach($Users_language as $users_language)
                                                                            <option value="{{$users_language->language_id}}">{{$users_language->language_name}}</option>
                                                                            @endforeach
                                                                        </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <input id="language_priority1" name="language_priority1" type="number" class="form-control" min="0" value="0" required="">
                                            </div>
                                            <br><br>
                                            <br>
                                </div>
                                
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
	
							<div class="col-md-2">
								<button type="button" id="submitBtn" class="send-btn k-button">Add Language</button>
							</div>
							<div class="col-md-5"></div>

						</div>
					<br>
				</div>
			</div>
		</div>
</form>
</section>
</section>
@endsection
<script src="{{asset('assets/kendoui/js/jquery.min.js')}}" type="text/javascript"></script>

@section('footer_scripts')
<script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/moment/js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/LanguageList.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseUserLanguage.js')}}"></script> 

@stop