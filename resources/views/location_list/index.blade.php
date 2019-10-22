@extends('layouts/default')
{{-- Page title --}}
@section('title')
	Merchant Locations
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
<!--page level css -->
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datedropper/datedropper.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/timedropper/css/timedropper.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jquerylabel/css/jquery-labelauty.css')}}"/>    
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/radio_checkbox.css')}}">
<!--end of page level css-->

<style type="text/css">

	#identityCityListForm label{
		font-weight: bold;
	}

	#identityCityListForm div.form-group{
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

	.group-margin{
	    padding-top: 5px;
    	padding-bottom: 5px;
	}

	.input-status{
		width: 10% !important;
	}
</style>

@section('content')
<section class="content-header">
	<h1>Merchant Location</h1>
	  <ol class="breadcrumb">
		  <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
		  <li><a href="#"> Merchant Location</a></li>
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
						<i class="fa fa-fw fa-users"></i> Merchant Location
					</h4>
				</div>
				<div class="panel-body">
					<form method='POST' action='{!!url("location")!!}' id="identityCityListForm">
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
						<div class="row">
							<div class="col-md-5">
								<div class="form-group">
									<label for="topologyTree">Locations</label>
									<select id="region_id" name="region_id[]" style="width: 100%"></select>
									<br>
									<div id="topologyTree" style="height:300px;overflow-y:scroll;"></div>
									<span id="treeError" style="color: red;display: none;">Please select at least one Node</span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-1"></div>
							<div class="col-md-2">
								<button type="button" id="submitBtn" class="send-btn k-button">Add Location</button>
							</div>
							<div class="col-md-5"></div>

						</div>
					</form>

					<div class="row">
						<div class="col-md-12">
							<div id="identityCityListGrid"></div>
						</div>
					</div>
				</div>
			</div>
		</div>		
		<!-- form-modal -->
		<div id="top_modal" class="modal fade animated position_modal" role="dialog">
			<div class="modal-dialog" style="width: 900px !important;">
				<div class="modal-content" style="width: 900px !important;">
					<div class="modal-header" style="background-color: #d9ecf5;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Merchant Location</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<form method='POST' action='{!!url("location")!!}' id="locationForm">
									{{ csrf_field() }}
									<div class="preloader" style="background: none !important; ">
										<div class="loader_img">
											<img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
										</div>
									</div>
									<div class="table-responsive">
										<table id=locationInfo class="table table-bordered">
											<thead>
												<tr>
													<th>Country</th>
													<th>State</th>
													<th>County</th>
													<th>City</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td id="countryName"></td>
													<td id="stateName"></td>
													<td id="countyName"></td>
													<td id="cityName"></td>
												</tr>
											</tbody>
										</table>
									</div>
									<div id="tabstrip">
										<ul>
											<li class="k-state-active">Postal</li>
											<li><i class="fa fa-plus addTab"></i></li>
										</ul>
										<div>
											<div id="postal_0">
												<div class="tab-content">
													<div class="innertab">
														<ul>
															<li class="k-state-active">Postal Address</li>
															<li class="openingHour">Opening Hours</li>
															<li class="holidayHour">Holiday Hours</li>
															<li class="order">Order</li>
															<li class="order">Reservation</li>
														</ul>
														<div>
															<div class="tab-content">
																<div class="row">
																	<div class="form-group">
																		<label for="subpremise" class="col-sm-2">Address 1</label>
																		<div class="col-sm-5">
																			<input id="subpremise_0" name="postals[0][subpremise]" type="text" class="form-control k-textbox subpremise" required validationMessage="Address Required" placeholder="None">
																		</div>
																	</div>
																</div>
																<div class="row">
																	<div class="form-group">
																		<label for="premise" class="col-sm-2">Address 2</label>
																		<div class="col-sm-5">
																			<input id="premise_0" name="postals[0][premise]" type="text" class="form-control k-textbox premise" placeholder="None"/>
																		</div>
																		<div class="col=md-4">
																			<button type="button" value="0" class="btn btn-success getLocation">Get Location</button>
																		</div>
																	</div>
																</div>
																<div class="row postal" style="display: none;">
																	<div class="form-group">
																		<label for="street_number_0" class="col-sm-2">Street Number</label>
																		<div class="col-sm-5">
																			<input id="street_number_0" name="postals[0][street_number]" style="width: 100%" />
																		</div>
																	</div>
																</div>
																<div class="row postal" style="display: none;">
																	<div class="form-group">
																		<label for="route_0" class="col-sm-2">Route</label>
																		<div class="col-sm-5">
																			<input id="route_0" name="postals[0][route]" style="width: 100%" />
																		</div>
																	</div>
																</div>
																<div class="row postal" style="display: none;">
																	<div class="form-group">
																		<label for="neighborhood_0" class="col-sm-2">Neighborhood</label>
																		<div class="col-sm-5">
																			<input style="width: 100%" id="neighborhood_0" name="postals[0][neighborhood]"/>
																		</div>
																	</div>
																</div>
																<div class="row postal" style="display: none;">
																	<div class="form-group">
																		<label for="postcode_0" class="col-sm-2">Postcode</label>
																		<div class="col-sm-5">
																			<input style="width: 100%" id="postcode_0" name="postals[0][postcode]"/>
																		</div>
																	</div>
																</div>
																<div class="row postal" style="display: none;">
																	<div class="form-group">
																		<label for="lat_0" class="col-sm-2">Latitude</label>
																		<div class="col-sm-5">
																			<input type="text" class="form-control k-textbox" name="postals[0][lat]" id="lat_0" style="width: 100%" />
																		</div>
																	</div>
																</div>
																<div class="row postal" style="display: none;">
																	<div class="form-group">
																		<label for="lng_0" class="col-sm-2">Longitude</label>
																		<div class="col-sm-5">
																			<input type="text" class="form-control k-textbox" name="postals[0][lng]" id="lng_0" style="width: 100%" />
																		</div>
																	</div>
																</div>
																<input type="hidden" id="list_id_0" name="postals[0][list_id]" value="0" >
																<input type="hidden" id="postal_id_0" name="postals[0][postal_id]" value="0" >
															</div>
														</div>
														<div>
															<div id="opening-flexible">
																<div class="tab-content">
																	<div class="form-group">
								                                        <label for="" class="col-sm-2 control-label"></label>
								                                        <div class="col-sm-5">
								                                            <div class="control-group control-group-2">
								                                                <div class="input-group" style="width: 48%;float: left;">
								                                                    <b>Open hour</b>
								                                                </div>
								                                                <div class="input-group" style="width: 48%;float: left;">
								                                                    <b>Close hour</b>
								                                                </div>
								                                            </div>
								                                        </div>
								                                    </div>															
																	<!-- Monday Start--> 
																	<div class="form-group" style="clear: both;">
								                                        <label class="col-sm-1 control-label input-status">
								                                            <span class="text-right">Monday</span>
								                                        </label>
								                                        <div class="info_0">
								                                            <div class="col-sm-9">
								                                                <div class="control-group control-group-3 group-margin">
								                                                    <div class="input-group" style="width: 29.1%; float: left;">
								                                                        <input type="text" name="postals[0][flexible_hours][0][0][open]" class="form-control timeclock" value="10:00">
								                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
								                                                    </div>
								                                                    <div class="input-group" style="width: 29.1%; margin-left: 16px; float: left;">
								                                                        <input type="text" name="postals[0][flexible_hours][0][0][close]" class="form-control timeclock" value="23:00">
								                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
								                                                    </div>
								                                                    <div class="btn-group btn-group-switch" data-toggle="buttons">
								                                                        <div class="make-switch col-sm-5" data-on="danger" data-off="default">
								                                                            <input type="checkbox" name="postals[0][flexible_hours][0][0][status]" class="hourstatus" value="1" data-on-text="Open" data-off-text="Closed">
								                                                        </div>
								                                                    </div>
								                                                    <a class="btn btn-primary btn-lg addShift" data-id="0" style="padding: 3px 5px;">
								                                                        <i class="fa fa-plus"></i>
								                                                    </a>
								                                                </div>
								                                            </div>
								                                        </div>
								                                    </div>
								                                    <!-- Monday End-->
								                                    <!-- Tuesday Start--> 
																	<div class="form-group">
								                                        <label class="col-sm-1 control-label input-status">
								                                            <span class="text-right">Tuesday</span>
								                                        </label>
								                                        <div class="info_1">
								                                            <div class="col-sm-9">
								                                                <div class="control-group control-group-3 group-margin">
								                                                    <div class="input-group" style="width: 29.1%; float: left;">
								                                                        <input type="text" name="postals[0][flexible_hours][1][0][open]" class="form-control timeclock" value="10:00">
								                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
								                                                    </div>
								                                                    <div class="input-group" style="width: 29.1%; margin-left: 16px; float: left;">
								                                                        <input type="text" name="postals[0][flexible_hours][1][0][close]" class="form-control timeclock" value="23:00">
								                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
								                                                    </div>
								                                                    <div class="btn-group btn-group-switch" data-toggle="buttons">
								                                                        <div class="make-switch col-sm-5" data-on="danger" data-off="default">
								                                                            <input type="checkbox" name="postals[0][flexible_hours][1][0][status]" class="hourstatus" value="1" data-on-text="Open" data-off-text="Closed">
								                                                        </div>
								                                                    </div>
								                                                    <a class="btn btn-primary btn-lg addShift" data-id="1"style="padding: 3px 5px;">
								                                                        <i class="fa fa-plus"></i>
								                                                    </a>
								                                                </div>
								                                            </div>
								                                        </div>
								                                    </div>
								                                    <!-- Tuesday End-->
								                                    <!-- Wednesday Start--> 
																	<div class="form-group">
								                                        <label class="col-sm-1 control-label input-status">
								                                            <span class="text-right">Wednesday</span>
								                                        </label>
								                                        <div class="info_2">
								                                            <div class="col-sm-9">
								                                                <div class="control-group control-group-3 group-margin">
								                                                    <div class="input-group" style="width: 29.1%; float: left;">
								                                                        <input type="text" name="postals[0][flexible_hours][2][0][open]" class="form-control timeclock" value="10:00">
								                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
								                                                    </div>
								                                                    <div class="input-group" style="width: 29.1%; margin-left: 16px; float: left;">
								                                                        <input type="text" name="postals[0][flexible_hours][2][0][close]" class="form-control timeclock" value="23:00">
								                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
								                                                    </div>
								                                                    <div class="btn-group btn-group-switch" data-toggle="buttons">
								                                                        <div class="make-switch col-sm-5" data-on="danger" data-off="default">
								                                                            <input type="checkbox" name="postals[0][flexible_hours][2][0][status]" class="hourstatus" value="1" data-on-text="Open" data-off-text="Closed">
								                                                        </div>
								                                                    </div>
								                                                    <a class="btn btn-primary btn-lg addShift" data-id="2" style="padding: 3px 5px;">
								                                                        <i class="fa fa-plus"></i>
								                                                    </a>
								                                                </div>
								                                            </div>
								                                        </div>
								                                    </div>
								                                    <!-- Wednesday End-->
								                                    <!-- Thursday Start--> 
																	<div class="form-group">
								                                        <label class="col-sm-1 control-label input-status">
								                                            <span class="text-right">Thursday</span>
								                                        </label>
								                                        <div class="info_3">
								                                            <div class="col-sm-9">
								                                                <div class="control-group control-group-3 group-margin">
								                                                    <div class="input-group" style="width: 29.1%; float: left;">
								                                                        <input type="text" name="postals[0][flexible_hours][3][0][open]" class="form-control timeclock" value="10:00">
								                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
								                                                    </div>
								                                                    <div class="input-group" style="width: 29.1%; margin-left: 16px; float: left;">
								                                                        <input type="text" name="postals[0][flexible_hours][3][0][close]" class="form-control timeclock" value="23:00">
								                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
								                                                    </div>
								                                                    <div class="btn-group btn-group-switch" data-toggle="buttons">
								                                                        <div class="make-switch col-sm-5" data-on="danger" data-off="default">
								                                                            <input type="checkbox" name="postals[0][flexible_hours][3][0][status]" class="hourstatus" value="1" data-on-text="Open" data-off-text="Closed">
								                                                        </div>
								                                                    </div>
								                                                    <a class="btn btn-primary btn-lg addShift" data-id="3" style="padding: 3px 5px;">
								                                                        <i class="fa fa-plus"></i>
								                                                    </a>
								                                                </div>
								                                            </div>
								                                        </div>
								                                    </div>
								                                    <!-- Thursday End-->
								                                    <!-- Friday Start--> 
																	<div class="form-group">
								                                        <label class="col-sm-1 control-label input-status">
								                                            <span class="text-right">Friday</span>
								                                        </label>
								                                        <div class="info_4">
								                                            <div class="col-sm-9">
								                                                <div class="control-group control-group-3 group-margin">
								                                                    <div class="input-group" style="width: 29.1%; float: left;">
								                                                        <input type="text" name="postals[0][flexible_hours][4][0][open]" class="form-control timeclock" value="10:00">
								                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
								                                                    </div>
								                                                    <div class="input-group" style="width: 29.1%; margin-left: 16px; float: left;">
								                                                        <input type="text" name="postals[0][flexible_hours][4][0][close]" class="form-control timeclock" value="23:00">
								                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
								                                                    </div>
								                                                    <div class="btn-group btn-group-switch" data-toggle="buttons">
								                                                        <div class="make-switch col-sm-5" data-on="danger" data-off="default">
								                                                            <input type="checkbox" name="postals[0][flexible_hours][4][0][status]" class="hourstatus" value="1" data-on-text="Open" data-off-text="Closed">
								                                                        </div>
								                                                    </div>
								                                                    <a class="btn btn-primary btn-lg addShift" data-id="4" style="padding: 3px 5px;">
								                                                        <i class="fa fa-plus"></i>
								                                                    </a>
								                                                </div>
								                                            </div>
								                                        </div>
								                                    </div>
								                                    <!-- Friday End-->
								                                    <!-- Saturday Start--> 
																	<div class="form-group">
								                                        <label class="col-sm-1 control-label input-status">
								                                            <span class="text-right">Saturday</span>
								                                        </label>
								                                        <div class="info_5">
								                                            <div class="col-sm-9">
								                                                <div class="control-group control-group-3 group-margin">
								                                                    <div class="input-group" style="width: 29.1%; float: left;">
								                                                        <input type="text" name="postals[0][flexible_hours][5][0][open]" class="form-control timeclock" value="10:00">
								                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
								                                                    </div>
								                                                    <div class="input-group" style="width: 29.1%; margin-left: 16px; float: left;">
								                                                        <input type="text" name="postals[0][flexible_hours][5][0][close]" class="form-control timeclock" value="23:00">
								                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
								                                                    </div>
								                                                    <div class="btn-group btn-group-switch" data-toggle="buttons">
								                                                        <div class="make-switch col-sm-5" data-on="danger" data-off="default">
								                                                            <input type="checkbox" name="postals[0][flexible_hours][5][0][status]" class="hourstatus" value="1" data-on-text="Open" data-off-text="Closed">
								                                                        </div>
								                                                    </div>
								                                                    <a class="btn btn-primary btn-lg addShift" data-id="5" style="padding: 3px 5px;">
								                                                        <i class="fa fa-plus"></i>
								                                                    </a>
								                                                </div>
								                                            </div>
								                                        </div>
								                                    </div>
								                                    <!-- Saturday End-->
								                                    <!-- Sunday Start--> 
																	<div class="form-group">
								                                        <label class="col-sm-1 control-label input-status">
								                                            <span class="text-right">Sunday</span>
								                                        </label>
								                                        <div class="info_6">
								                                            <div class="col-sm-9">
								                                                <div class="control-group control-group-3 group-margin">
								                                                    <div class="input-group" style="width: 29.1%; float: left;">
								                                                        <input type="text" name="postals[0][flexible_hours][6][0][open]" class="form-control timeclock" value="10:00">
								                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
								                                                    </div>
								                                                    <div class="input-group" style="width: 29.1%; margin-left: 16px; float: left;">
								                                                        <input type="text" name="postals[0][flexible_hours][6][0][close]" class="form-control timeclock" value="23:00">
								                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
								                                                    </div>
								                                                    <div class="btn-group btn-group-switch" data-toggle="buttons">
								                                                        <div class="make-switch col-sm-5" data-on="danger" data-off="default">
								                                                            <input type="checkbox" name="postals[0][flexible_hours][6][0][status]" class="hourstatus" value="1" data-on-text="Open" data-off-text="Closed">
								                                                        </div>
								                                                    </div>
								                                                    <a class="btn btn-primary btn-lg addShift" data-id="6" style="padding: 3px 5px;">
								                                                        <i class="fa fa-plus"></i>
								                                                    </a>
								                                                </div>
								                                            </div>
								                                        </div>
								                                    </div>
								                                    <!-- Sunday End-->
								                                </div>    
						                                    </div> 
														</div>
														<div>
															<div id="holiday-opening-flexible">
																<div class="tab-content">
																	<div class="form-group" style="border-top: 1px solid #ddd;border-bottom: 1px solid #ddd;">
								                                        <a class="btn btn-primary btn-lg addNewHoliday" style="padding: 5px 7px; margin: 7px 0 7px; margin-left: 10px;">
								                                            <i class="fa fa-plus"></i>
								                                        </a>
								                                    </div>
																	<div class="form-group">
								                                        <div class="col-sm-5">
							                                                <div class="control-group control-group-3 group-margin">								                                                	
							                                                    <div class="input-group" style="width:46%;float: left;">
							                                                       	<label class="control-label">Holiday Name</label>
							                                                    </div>
							                                                    <div class="input-group" style="width:48%;margin-left: 16px; float: left;">
							                                                        <label class="control-label">Holiday Date</label>
							                                                    </div>
							                                                </div>
							                                            </div>							                                           
							                                            <div class="col-sm-6">
							                                                <div class="control-group control-group-3 group-margin">								                                                	
							                                                    <div class="input-group" style="width: 29.1%; float: left;">
							                                                        <label class="control-label">Opening Time</label>
							                                                    </div>
							                                                    <div class="input-group" style="width: 29.1%; margin-left: 16px; float: left;">
							                                                        <label class="control-label">Closing Time</label>
							                                                    </div>
							                                                    <div class="input-group" style="width: 29.1%; margin-left: 16px; float: left;">
							                                                        <label class="control-label">Status</label>
							                                                    </div>								                                                    
							                                                </div>
							                                            </div>
								                                    </div>														
																	<!-- Holiday Start--> 
																	<div class="holidays">
																		<div class="holiday_0">
																			<div class="form-group" style="clear: both;">
										                                        <div class="col-sm-5">
									                                                <div class="control-group control-group-3 group-margin">								                                                	
									                                                    <div class="input-group" style="width:46%;float: left;">
									                                                        <select name="postals[0][holiday_hours][0][0][holiday_id]" class="select21 form-control holiday_id">
																							    <option></option>																							    
																							</select>
									                                                    </div>
									                                                    <div class="input-group" style="width:48%;margin-left: 16px; float: left;">
									                                                        <input type="text" name="postals[0][holiday_hours][0][0][date]" value="0000-00-00" class=" form-control holiday_date" placeholder="YYYY-MM-DD"/>
																						    <div class="input-group-addon"><i class="fa fa-fw fa-calendar"></i></div>
									                                                    </div>
									                                                </div>
									                                            </div>							                                           
										                                        <div class="holiday_info_0">
										                                            <div class="col-sm-6">
										                                                <div class="control-group control-group-3 group-margin">								                                                	
										                                                    <div class="input-group" style="width: 29.1%; float: left;">
										                                                        <input type="text" name="postals[0][holiday_hours][0][0][open]" class="form-control timeclock" value="10:00">
										                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
										                                                    </div>
										                                                    <div class="input-group" style="width: 29.1%; margin-left: 16px; float: left;">
										                                                        <input type="text" name="postals[0][holiday_hours][0][0][close]" class="form-control timeclock" value="23:00">
										                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
										                                                    </div>
										                                                    <div class="btn-group btn-group-switch" data-toggle="buttons" style="float: left;">
										                                                        <div class="make-switch col-sm-5" data-on="danger" data-off="default">
										                                                            <input type="checkbox" name="postals[0][holiday_hours][0][0][status]" class="hourstatus" value="1" data-on-text="Open" data-off-text="Closed">
										                                                        </div>
										                                                    </div>
										                                                    <a class="btn btn-primary btn-lg addHolidayShift group-margin" data-tab-id="0" data-holiday-id="0" style="padding: 3px 5px;">
										                                                        <i class="fa fa-plus"></i>
										                                                    </a>
										                                                </div>
										                                            </div>
										                                        </div>
										                                    </div>										                                    
										                                </div>    
									                                </div>
								                                    <!-- Holiday End-->
								                                </div>    
						                                    </div> 
														</div>
														<div>
															<div id="order">
																<div class="tab-content">
																	<div class="row">
																		<div class="form-group">
										                                    <label for="offer_delivery" class="col-sm-2 control-label">Offer Delivery</label>
										                                    <div class="make-switch col-sm-3" data-on="danger" data-off="default">
										                                        <input type="checkbox" name="postals[0][offer_delivery]" id="offer_delivery" data-on-text="YES" data-off-text="NO" class="orderstatus">
										                                    </div>
										                                </div>
																	</div>
																	<div class="row">
																		<div class="form-group">
										                                    <label for="offer_collection" class="col-sm-2 control-label">Offer Pick-up</label>
										                                    <div class="make-switch col-sm-3" data-on="danger" data-off="default">
										                                        <input type="checkbox" name="postals[0][offer_collection]" id="offer_collection" data-on-text="YES" data-off-text="NO" class="orderstatus">
										                                    </div>
										                                </div>
									                                </div>
									                                <div class="row">
										                                <div class="form-group">
										                                    <label for="delivery_time" class="col-sm-2 control-label">Delivery Time
										                                        <span class="help-block" style="font-size: 82%;">Set number of minutes an order will be delivered after being placed, or set to 0 to use default</span>
										                                    </label>
										                                    <div class="input-group col-sm-3" style="padding-left: 15px;">
										                                        <input id="delivery_time" name="postals[0][delivery_time]" type="text" class="form-control">
										                                        <span class="input-group-addon">minutes</span>
										                                    </div>
										                                </div>
										                            </div>
										                            <div class="row">    
										                                <div class="form-group">
										                                    <label for="collection_time" class="col-sm-2 control-label">Pick-up Time
										                                        <span class="help-block" style="font-size: 82%;">Set number of minutes an order will be ready for pick-up after being placed, or set to 0 to use default</span>
										                                    </label>
										                                    <div class="input-group col-sm-3" style="padding-left: 15px;">
										                                        <input id="collection_time" name="postals[0][collection_time]" type="text" class="form-control">
										                                        <span class="input-group-addon">minutes</span>
										                                    </div>
										                                </div>
										                            </div>
										                            <div class="row">    
										                                <div class="form-group">
										                                    <label for="last_order_time" class="col-sm-2 control-label">Last Order Time
										                                        <span class="help-block" style="font-size: 82%;">Set number of minutes before closing time for last order, or set to 0 to use closing hour.</span>
										                                    </label>
										                                    <div class="input-group col-sm-3" style="padding-left: 15px;">
										                                        <input id="last_order_time" name="postals[0][last_order_time]" type="text" class="form-control">
										                                        <span class="input-group-addon">minutes</span>
										                                    </div>
										                                </div>
										                            </div>
										                            <div class="row">    
										                                <div class="form-group">
										                                    <label for="future_orders" class="col-sm-2 control-label">Accept Future Orders
										                                        <span class="help-block" style="font-size: 82%;">Allow customer to place order for a later time when restaurant is closed for delivery or pick-up during opening hours
										                                        </span>
										                                    </label>
										                                    <div class="make-switch col-sm-3" data-on="danger" data-off="default">
										                                        <input type="checkbox" name="postals[0][future_orders]" id="future_orders" data-on-text="YES" data-off-text="NO" class="orderstatus">
										                                    </div>
										                                </div>
										                            </div> 
										                            <div id="future-orders-days" class="row">
									                                    <div class="form-group">
									                                        <label for="input-delivery-days" class="col-sm-2 control-label">Future Order Days In Advance
									                                            <span class="help-block" style="font-size: 82%;">Set the number of days in advance to allow customer to place a delivery or pick-up order for a later time.
									                                            </span>
									                                        </label>
									                                        <div class="col-sm-7">
									                                            <div class="control-group control-group-2">
									                                                <div class="input-group" style="width: 48%;float: left;">
									                                                    <span class="input-group-addon"><b>Delivery:</b></span>
									                                                    <input type="text" name="postals[0][future_order_delivery_days]" class="form-control" value="">
									                                                    <span class="input-group-addon">days</span>
									                                                </div>
									                                                <div class="input-group" style="width: 48%;float: left; margin-left: 18px;">
									                                                    <span class="input-group-addon"><b>Pick-up:</b></span>
									                                                    <input type="text" name="postals[0][future_order_collection_days]" class="form-control" value="">
									                                                    <span class="input-group-addon">days</span>
									                                                </div>
									                                            </div>
									                                        </div>
									                                    </div>
									                                </div>   
								                                </div>    
						                                    </div> 
														</div>	
														<div>
															<div id="reservation">
																<div class="tab-content">
																	<div class="row">
																		<div class="form-group">
										                                    <label for="reservation_time_interval" class="col-sm-2 control-label">Time Interval
										                                        <span class="help-block" style="font-size: 82%;">Set the number of minutes between each reservation time, Leave as 0 to use system setting value</span>
										                                    </label>
										                                    <div class="input-group col-sm-3" style="padding-left: 15px;">
										                                        <input name="postals[0][reservation_time_interval]" type="text" class="form-control">
										                                        <span class="input-group-addon">minutes</span>
										                                    </div>
										                                </div>
																	</div>
																	<div class="row">
																		<div class="form-group">
										                                    <label for="reservation_stay_time" class="col-sm-2 control-label">Stay Time
										                                        <span class="help-block" style="font-size: 82%;">Set in minutes the average time a guest will stay at a table, Leave as 0 to use system setting value</span>
										                                    </label>
										                                    <div class="input-group col-sm-3" style="padding-left: 15px;">
										                                        <input name="postals[0][reservation_stay_time]" type="text" class="form-control">
										                                        <span class="input-group-addon">minutes</span>
										                                    </div>
										                                </div>										                                
																	</div>
																	<div class="row">
																		<div class="form-group">
										                                    <label for="input-table" class="col-sm-2 control-label">Tables</label>
										                                    <div class="col-sm-4">
										                                        <select id="location_tables" class="select21 form-control reservation_seating">
										                                            <option></option>
										                                            @foreach($reservations_seatings as $reservations_seating)
										                                                <option value="{{$reservations_seating['seating_id']}}" data-minimum="{{$reservations_seating['min_capacity']}}" data-capacity="{{$reservations_seating['max_capacity']}}">{{$reservations_seating['seating_name']}}</option>
										                                            @endforeach
										                                        </select>
										                                    </div>
										                                </div>
																	</div>
																	<div class="row">
									                                    <div id="table-box" class="col-sm-12 wrap-top">
									                                        <div class="table-responsive">
									                                            <table class="table table-striped">
									                                                <thead>
									                                                    <tr>
									                                                        <th width="40%">Name</th>
									                                                        <th>Minimum</th>
									                                                        <th>Capacity</th>
									                                                        <th>Remove</th>
									                                                    </tr>
									                                                </thead>
									                                                <tbody>
									                                                </tbody>
									                                            </table>
									                                        </div>
									                                    </div>
									                                </div>
								                                </div>    
						                                    </div> 
														</div>		
													</div>
												</div>
											</div>
										</div>
										<div style="display: none;"></div>
									</div>
									<div class="modal-footer" style="padding-left: 33%;text-align:inherit">
										<button type="button" id="updateLocation" value="1" class="btn btn-success">Update</button>
										<button type="button" class="btn btn-danger" data-dismiss="modal">
											Close
										</button>
									</div>
									<input type="hidden" id="city_name" name="city_name" value="" >
									<input type="hidden" id="postal_code_max" name="postal_code_max" value="" >
									<input type="hidden" id="list_identity_id" name="identity_id" value="" >
									<input type="hidden" id="list_identity_table_id" name="identity_table_id" value="" >
									<input type="hidden" id="list_location_city_id" name="location_city_id" value="" >
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div id="demo" style="display: none;">
			<div id="" class="postaltab">
				<div class="tab-content">				
					<div class="innertab">
						<ul>
							<li class="k-state-active">Postal Address</li>
							<li class="openingHour">Opening Hours</li>
							<li class="holidayHour">Holiday Hours</li>
							<li class="order">Order</li>
							<li class="order">Reservation</li>	
						</ul>
						<div>
							<div class="tab-content">
								<div class="row">
									<div class="form-group">
										<label for="subpremise" class="col-sm-2">Address 1</label>
										<div class="col-sm-5">
											<input id="subpremise" name="" type="text" class="form-control k-textbox subpremise" required validationMessage="Address Required" placeholder="None">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="form-group">
										<label for="premise" class="col-sm-2">Address 2</label>
										<div class="col-sm-5">
											<input id="premise" name="" type="text" class="form-control k-textbox premise" placeholder="None"/>
										</div>
										<div class="col=md-4">
											<button type="button" value="0" class="btn btn-success getLocation">Get Location</button>
										</div>
									</div>
								</div>
								<div class="row postal" style="display: none;">
									<div class="form-group">
										<label for="street_number" class="col-sm-2">Street Number</label>
										<div class="col-sm-5">
											<input id="street_number" style="width: 100%" />
										</div>
									</div>
								</div>
								<div class="row postal" style="display: none;">
									<div class="form-group">
										<label for="route" class="col-sm-2">Route</label>
										<div class="col-sm-5">
											<input id="route" style="width: 100%" />
										</div>
									</div>
								</div>
								<div class="row postal" style="display: none;">
									<div class="form-group">
										<label for="neighborhood" class="col-sm-2">Neighborhood</label>
										<div class="col-sm-5">
											<input style="width: 100%" id="neighborhood" />
										</div>
									</div>
								</div>
								<div class="row postal" style="display: none;">
									<div class="form-group">
										<label for="postcode" class="col-sm-2">Postcode</label>
										<div class="col-sm-5">
											<input style="width: 100%" id="postcode" />
										</div>
									</div>
								</div>
								<div class="row postal" style="display: none;">
									<div class="form-group">
										<label for="lat" class="col-sm-2">Latitude</label>
										<div class="col-sm-5">
											<input type="text" class="form-control k-textbox" name="" id="lat" style="width: 100%" />
										</div>
									</div>
								</div>
								<div class="row postal" style="display: none;">
									<div class="form-group">
										<label for="lng" class="col-sm-2">Longitude</label>
										<div class="col-sm-5">
											<input type="text" class="form-control k-textbox" name="" id="lng" style="width: 100%" />
										</div>
									</div>
								</div>
								<input type="hidden" id="list_id" name="" value="" >
								<input type="hidden" id="postal_id" name="" value="" >
							</div>
						</div>
						<div>
							<div id="opening-flexible">
								<div class="tab-content">
									<div class="form-group">
	                                    <label for="" class="col-sm-2 control-label"></label>
	                                    <div class="col-sm-5">
	                                        <div class="control-group control-group-2">
	                                            <div class="input-group" style="width: 48%;float: left;">
	                                                <b>Open hour</b>
	                                            </div>
	                                            <div class="input-group" style="width: 48%;float: left;">
	                                                <b>Close hour</b>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>															
									<!-- Monday Start--> 
									<div class="form-group" style="clear: both;">
	                                    <label class="col-sm-1 control-label input-status">
	                                        <span class="text-right">Monday</span>
	                                    </label>
	                                    <div class="info_0">
	                                        <div class="col-sm-9 group-margin">
	                                            <div class="control-group control-group-3">
	                                                <div class="input-group" style="width: 29.1%; float: left;">
	                                                    <input type="text" id="monday_open" name="" class="form-control timeclock" value="10:00">
	                                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
	                                                </div>
	                                                <div class="input-group" style="width: 29.1%; margin-left: 16px; float: left;">
	                                                    <input type="text" id="monday_close" name="" class="form-control timeclock" value="23:00">
	                                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
	                                                </div>
	                                                <div class="btn-group btn-group-switch" data-toggle="buttons">
	                                                    <div class="make-switch col-sm-5" data-on="danger" data-off="default">
	                                                        <input type="checkbox" id="monday_status" name="" class="switch" value="1" data-on-text="Open" data-off-text="Closed">
	                                                    </div>
	                                                </div>
	                                                <a class="btn btn-primary btn-lg addShift" data-id="0" style="padding: 3px 5px;">
	                                                    <i class="fa fa-plus"></i>
	                                                </a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                                <!-- Monday End-->
	                                <!-- Tuesday Start--> 
									<div class="form-group">
	                                    <label class="col-sm-1 control-label input-status">
	                                        <span class="text-right">Tuesday</span>
	                                    </label>
	                                    <div class="info_1">
	                                        <div class="col-sm-9 group-margin">
	                                            <div class="control-group control-group-3">
	                                                <div class="input-group" style="width: 29.1%; float: left;">
	                                                    <input type="text" id="tuesday_open" name="" class="form-control timeclock" value="10:00">
	                                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
	                                                </div>
	                                                <div class="input-group" style="width: 29.1%; margin-left: 16px; float: left;">
	                                                    <input type="text" id="tuesday_close" name="" class="form-control timeclock" value="23:00">
	                                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
	                                                </div>
	                                                <div class="btn-group btn-group-switch" data-toggle="buttons">
	                                                    <div class="make-switch col-sm-5" data-on="danger" data-off="default">
	                                                        <input type="checkbox" id="tuesday_status" name="" class="switch" value="1" data-on-text="Open" data-off-text="Closed">
	                                                    </div>
	                                                </div>
	                                                <a class="btn btn-primary btn-lg addShift" data-id="1" style="padding: 3px 5px;">
	                                                    <i class="fa fa-plus"></i>
	                                                </a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                                <!-- Tuesday End-->
	                                <!-- Wednesday Start--> 
									<div class="form-group">
	                                    <label class="col-sm-1 control-label input-status">
	                                        <span class="text-right">Wednesday</span>
	                                    </label>
	                                    <div class="info_2">
	                                        <div class="col-sm-9 group-margin">
	                                            <div class="control-group control-group-3">
	                                                <div class="input-group" style="width: 29.1%; float: left;">
	                                                    <input type="text" id="wednesday_open" name="" class="form-control timeclock" value="10:00">
	                                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
	                                                </div>
	                                                <div class="input-group" style="width: 29.1%; margin-left: 16px; float: left;">
	                                                    <input type="text" id="wednesday_close" name="" class="form-control timeclock" value="23:00">
	                                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
	                                                </div>
	                                                <div class="btn-group btn-group-switch" data-toggle="buttons">
	                                                    <div class="make-switch col-sm-5" data-on="danger" data-off="default">
	                                                        <input type="checkbox" id="wednesday_status" name="" class="switch" value="1" data-on-text="Open" data-off-text="Closed">
	                                                    </div>
	                                                </div>
	                                                <a class="btn btn-primary btn-lg addShift" data-id="2" style="padding: 3px 5px;">
	                                                    <i class="fa fa-plus"></i>
	                                                </a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                                <!-- Wednesday End-->
	                                <!-- Thursday Start--> 
									<div class="form-group">
	                                    <label class="col-sm-1 control-label input-status">
	                                        <span class="text-right">Thursday</span>
	                                    </label>
	                                    <div class="info_3">
	                                        <div class="col-sm-9 group-margin">
	                                            <div class="control-group control-group-3">
	                                                <div class="input-group" style="width: 29.1%; float: left;">
	                                                    <input type="text" id="thursday_open" name="" class="form-control timeclock" value="10:00">
	                                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
	                                                </div>
	                                                <div class="input-group" style="width: 29.1%; margin-left: 16px; float: left;">
	                                                    <input type="text" id="thursday_close" name="" class="form-control timeclock" value="23:00">
	                                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
	                                                </div>
	                                                <div class="btn-group btn-group-switch" data-toggle="buttons">
	                                                    <div class="make-switch col-sm-5" data-on="danger" data-off="default">
	                                                        <input type="checkbox" id="thursday_status" name="" class="switch" value="1" data-on-text="Open" data-off-text="Closed">
	                                                    </div>
	                                                </div>
	                                                <a class="btn btn-primary btn-lg addShift" data-id="3" style="padding: 3px 5px;">
	                                                    <i class="fa fa-plus"></i>
	                                                </a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                                <!-- Thursday End-->
	                                <!-- Friday Start--> 
									<div class="form-group">
	                                    <label class="col-sm-1 control-label input-status">
	                                        <span class="text-right">Friday</span>
	                                    </label>
	                                    <div class="info_4">
	                                        <div class="col-sm-9 group-margin">
	                                            <div class="control-group control-group-3">
	                                                <div class="input-group" style="width: 29.1%; float: left;">
	                                                    <input type="text" id="friday_open" name="" class="form-control timeclock" value="10:00">
	                                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
	                                                </div>
	                                                <div class="input-group" style="width: 29.1%; margin-left: 16px; float: left;">
	                                                    <input type="text" id="friday_close" name="" class="form-control timeclock" value="23:00">
	                                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
	                                                </div>
	                                                <div class="btn-group btn-group-switch" data-toggle="buttons">
	                                                    <div class="make-switch col-sm-5" data-on="danger" data-off="default">
	                                                        <input type="checkbox" id="friday_status" name="" class="switch" value="1" data-on-text="Open" data-off-text="Closed">
	                                                    </div>
	                                                </div>
	                                                <a class="btn btn-primary btn-lg addShift" data-id="4" style="padding: 3px 5px;">
	                                                    <i class="fa fa-plus"></i>
	                                                </a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                                <!-- Friday End-->
	                                <!-- Saturday Start--> 
									<div class="form-group">
	                                    <label class="col-sm-1 control-label input-status">
	                                        <span class="text-right">Saturday</span>
	                                    </label>
	                                    <div class="info_5">
	                                        <div class="col-sm-9 group-margin">
	                                            <div class="control-group control-group-3">
	                                                <div class="input-group" style="width: 29.1%; float: left;">
	                                                    <input type="text" id="saturday_open" name="" class="form-control timeclock" value="10:00">
	                                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
	                                                </div>
	                                                <div class="input-group" style="width: 29.1%; margin-left: 16px; float: left;">
	                                                    <input type="text" id="saturday_close" name="" class="form-control timeclock" value="23:00">
	                                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
	                                                </div>
	                                                <div class="btn-group btn-group-switch" data-toggle="buttons">
	                                                    <div class="make-switch col-sm-5" data-on="danger" data-off="default">
	                                                        <input type="checkbox" id="saturday_status" name="" class="switch" value="1" data-on-text="Open" data-off-text="Closed">
	                                                    </div>
	                                                </div>
	                                                <a class="btn btn-primary btn-lg addShift" data-id="5" style="padding: 3px 5px;">
	                                                    <i class="fa fa-plus"></i>
	                                                </a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                                <!-- Saturday End-->
	                                <!-- Sunday Start--> 
									<div class="form-group">
	                                    <label class="col-sm-1 control-label input-status">
	                                        <span class="text-right">Sunday</span>
	                                    </label>
	                                    <div class="info_6">
	                                        <div class="col-sm-9 group-margin">
	                                            <div class="control-group control-group-3">
	                                                <div class="input-group" style="width: 29.1%; float: left;">
	                                                    <input type="text" id="sunday_open" name="" class="form-control timeclock" value="10:00">
	                                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
	                                                </div>
	                                                <div class="input-group" style="width: 29.1%; margin-left: 16px; float: left;">
	                                                    <input type="text" id="sunday_close" name="" class="form-control timeclock" value="23:00">
	                                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
	                                                </div>
	                                                <div class="btn-group btn-group-switch" data-toggle="buttons">
	                                                    <div class="make-switch col-sm-5" data-on="danger" data-off="default">
	                                                        <input type="checkbox" id="sunday_status" name="" class="switch" value="1" data-on-text="Open" data-off-text="Closed">
	                                                    </div>
	                                                </div>
	                                                <a class="btn btn-primary btn-lg addShift" data-id="6" style="padding: 3px 5px;">
	                                                    <i class="fa fa-plus"></i>
	                                                </a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                                <!-- Sunday End-->
	                            </div>    
	                        </div> 
						</div>
						<div>
							<div id="holiday-opening-flexible">
								<div class="tab-content">
									<div class="form-group" style="border-top: 1px solid #ddd;border-bottom: 1px solid #ddd;">
                                        <a class="btn btn-primary btn-lg addNewHoliday" style="padding: 5px 7px; margin: 7px 0 7px; margin-left: 10px;">
                                            <i class="fa fa-plus"></i>
                                        </a>
                                    </div>
									<div class="form-group">
                                        <div class="col-sm-5">
                                            <div class="control-group control-group-3 group-margin">								                                                	
                                                <div class="input-group" style="width:46%;float: left;">
                                                   	<label class="control-label">Holiday Name</label>
                                                </div>
                                                <div class="input-group" style="width:48%;margin-left: 16px; float: left;">
                                                    <label class="control-label">Holiday Date</label>
                                                </div>
                                            </div>
                                        </div>							                                           
                                        <div class="col-sm-6">
                                            <div class="control-group control-group-3 group-margin">								                                                	
                                                <div class="input-group" style="width: 29.1%; float: left;">
                                                    <label class="control-label">Opening Time</label>
                                                </div>
                                                <div class="input-group" style="width: 29.1%; margin-left: 16px; float: left;">
                                                    <label class="control-label">Closing Time</label>
                                                </div>
                                                <div class="input-group" style="width: 29.1%; margin-left: 16px; float: left;">
                                                    <label class="control-label">Status</label>
                                                </div>								                                                    
                                            </div>
                                        </div>
                                    </div>														
									<!-- Holiday Start--> 
									<div class="holidays">
										<div class="holiday">
											<div class="form-group" style="clear: both;">
		                                        <div class="col-sm-5">
	                                                <div class="control-group control-group-3 group-margin">								                                                	
	                                                    <div class="input-group" style="width:46%;float: left;">
	                                                        <select id="holiday_id" name="" class="select21 form-control holiday_id">
															    <option></option>															    
															</select>
	                                                    </div>
	                                                    <div class="input-group" style="width:48%;margin-left: 16px; float: left;">
	                                                        <input type="text" id="holiday_date" name="" value="0000-00-00" class=" form-control holiday_date" placeholder="YYYY-MM-DD"/>
														    <div class="input-group-addon"><i class="fa fa-fw fa-calendar"></i></div>
	                                                    </div>
	                                                </div>
	                                            </div>							                                           
		                                        <div class="holiday_info">
		                                            <div class="col-sm-6">
		                                                <div class="control-group control-group-3 group-margin">								                                                	
		                                                    <div class="input-group" style="width: 29.1%; float: left;">
		                                                        <input type="text" id="holiday_open" name="" class="form-control timeclock" value="10:00">
		                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
		                                                    </div>
		                                                    <div class="input-group" style="width: 29.1%; margin-left: 16px; float: left;">
		                                                        <input type="text" id="holiday_close" name="" class="form-control timeclock" value="23:00">
		                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
		                                                    </div>
		                                                    <div class="btn-group btn-group-switch" data-toggle="buttons" style="float: left;">
		                                                        <div class="make-switch col-sm-5" data-on="danger" data-off="default">
		                                                            <input type="checkbox" id="holiday_status" name="" class="hourstatus" value="1" data-on-text="Open" data-off-text="Closed">
		                                                        </div>
		                                                    </div>
		                                                    <a class="btn btn-primary btn-lg addHolidayShift group-margin" style="padding: 3px 5px;">
		                                                        <i class="fa fa-plus"></i>
		                                                    </a>
		                                                </div>
		                                            </div>
		                                        </div>
		                                    </div>
		                                </div>    
	                                </div>
                                    <!-- Holiday End-->
                                </div>    
                            </div> 
						</div>
						<div>
							<div id="order">
								<div class="tab-content">
									<div class="row">
										<div class="form-group">
		                                    <label for="offer_delivery" class="col-sm-2 control-label">Offer Delivery</label>
		                                    <div class="make-switch col-sm-3" data-on="danger" data-off="default">
		                                        <input type="checkbox" name="" id="offer_delivery" data-on-text="YES" data-off-text="NO" class="orderstatus">
		                                    </div>
		                                </div>
									</div>
									<div class="row">
										<div class="form-group">
		                                    <label for="offer_collection" class="col-sm-2 control-label">Offer Pick-up</label>
		                                    <div class="make-switch col-sm-3" data-on="danger" data-off="default">
		                                        <input type="checkbox" name="" id="offer_collection" data-on-text="YES" data-off-text="NO" class="orderstatus">
		                                    </div>
		                                </div>
	                                </div>
	                                <div class="row">
		                                <div class="form-group">
		                                    <label for="delivery_time" class="col-sm-2 control-label">Delivery Time
		                                        <span class="help-block" style="font-size: 82%;">Set number of minutes an order will be delivered after being placed, or set to 0 to use default</span>
		                                    </label>
		                                    <div class="input-group col-sm-3" style="padding-left: 15px;">
		                                        <input id="delivery_time" name="" type="text" class="form-control">
		                                        <span class="input-group-addon">minutes</span>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="row">    
		                                <div class="form-group">
		                                    <label for="collection_time" class="col-sm-2 control-label">Pick-up Time
		                                        <span class="help-block" style="font-size: 82%;">Set number of minutes an order will be ready for pick-up after being placed, or set to 0 to use default</span>
		                                    </label>
		                                    <div class="input-group col-sm-3" style="padding-left: 15px;">
		                                        <input id="collection_time" name="" type="text" class="form-control">
		                                        <span class="input-group-addon">minutes</span>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="row">    
		                                <div class="form-group">
		                                    <label for="last_order_time" class="col-sm-2 control-label">Last Order Time
		                                        <span class="help-block" style="font-size: 82%;">Set number of minutes before closing time for last order, or set to 0 to use closing hour.</span>
		                                    </label>
		                                    <div class="input-group col-sm-3" style="padding-left: 15px;">
		                                        <input id="last_order_time" name="" type="text" class="form-control">
		                                        <span class="input-group-addon">minutes</span>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="row">    
		                                <div class="form-group">
		                                    <label for="future_orders" class="col-sm-2 control-label">Accept Future Orders
		                                        <span class="help-block" style="font-size: 82%;">Allow customer to place order for a later time when restaurant is closed for delivery or pick-up during opening hours
		                                        </span>
		                                    </label>
		                                    <div class="make-switch col-sm-3" data-on="danger" data-off="default">
		                                        <input type="checkbox" name="" id="future_orders" data-on-text="YES" data-off-text="NO" class="orderstatus">
		                                    </div>
		                                </div>
		                            </div> 
		                            <div id="future-orders-days" class="row">
	                                    <div class="form-group">
	                                        <label for="input-delivery-days" class="col-sm-2 control-label">Future Order Days In Advance
	                                            <span class="help-block" style="font-size: 82%;">Set the number of days in advance to allow customer to place a delivery or pick-up order for a later time.
	                                            </span>
	                                        </label>
	                                        <div class="col-sm-7">
	                                            <div class="control-group control-group-2">
	                                                <div class="input-group" style="width: 48%;float: left;">
	                                                    <span class="input-group-addon"><b>Delivery:</b></span>
	                                                    <input type="text" name="" id="future_order_delivery_days"class="form-control" value="">
	                                                    <span class="input-group-addon">days</span>
	                                                </div>
	                                                <div class="input-group" style="width: 48%;float: left; margin-left: 18px;">
	                                                    <span class="input-group-addon"><b>Pick-up:</b></span>
	                                                    <input type="text" name="" id="future_order_collection_days" class="form-control" value="">
	                                                    <span class="input-group-addon">days</span>
	                                                </div>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>   
	                            </div>    
	                        </div>
                        </div>	
                        <div>
							<div id="reservation">
								<div class="tab-content">
									<div class="row">
										<div class="form-group">
		                                    <label for="reservation_time_interval" class="col-sm-2 control-label">Time Interval
		                                        <span class="help-block" style="font-size: 82%;">Set the number of minutes between each reservation time, Leave as 0 to use system setting value</span>
		                                    </label>
		                                    <div class="input-group col-sm-3" style="padding-left: 15px;">
		                                        <input id="reservation_time_interval" name="" type="text" class="form-control">
		                                        <span class="input-group-addon">minutes</span>
		                                    </div>
		                                </div>
									</div>
									<div class="row">
										<div class="form-group">
		                                    <label for="reservation_stay_time" class="col-sm-2 control-label">Stay Time
		                                        <span class="help-block" style="font-size: 82%;">Set in minutes the average time a guest will stay at a table, Leave as 0 to use system setting value</span>
		                                    </label>
		                                    <div class="input-group col-sm-3" style="padding-left: 15px;">
		                                        <input id="reservation_stay_time" name="" type="text" class="form-control">
		                                        <span class="input-group-addon">minutes</span>
		                                    </div>
		                                </div>										                                
									</div>
									<div class="row">
										<div class="form-group">
		                                    <label for="input-table" class="col-sm-2 control-label">Tables</label>
		                                    <div class="col-sm-4">
		                                        <select class="select21 form-control reservation_seating">
		                                            <option></option>
		                                            @foreach($reservations_seatings as $reservations_seating)
		                                                <option value="{{$reservations_seating['seating_id']}}" data-minimum="{{$reservations_seating['min_capacity']}}" data-capacity="{{$reservations_seating['max_capacity']}}">{{$reservations_seating['seating_name']}}</option>
		                                            @endforeach
		                                        </select>
		                                    </div>
		                                </div>
									</div>
									<div class="row">
	                                    <div id="table-box" class="col-sm-12 wrap-top">
	                                        <div class="table-responsive">
	                                            <table class="table table-striped">
	                                                <thead>
	                                                    <tr>
	                                                        <th width="40%">Name</th>
	                                                        <th>Minimum</th>
	                                                        <th>Capacity</th>
	                                                        <th>Remove</th>
	                                                    </tr>
	                                                </thead>
	                                                <tbody>
	                                                </tbody>
	                                            </table>
	                                        </div>
	                                    </div>
	                                </div>
                                </div>    
                            </div> 
						</div>
					</div>				
				</div>
			</div>	
		</div>
		<!-- form-modal end -->
	</section>
</section>
@endsection
<script src="{{asset('assets/kendoui/js/jquery.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/vendors/moment/js/moment.min.js')}}"></script>

@section('footer_scripts')
<script type="text/javascript" src="{{asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapwizard/js/jquery.bootstrap.wizard.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/jquerylabel/js/jquery-labelauty.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datedropper/datedropper.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/timedropper/js/timedropper.js')}}" ></script>

<script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/LocationListIndex.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/LocationPostalIndex.js')}}"></script>
@stop