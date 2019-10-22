@extends('layouts/default')
{{-- Page title --}}
@section('title')
Venue
@parent
@stop
{{-- page level styles --}}
@section('header_styles')
	<style>
		
		#tabstrip div.row{
			margin: 10px 0px;
		}

		#locationInfo thead tr{
			width: 100%;
			background-color: #d9ecf5;
			color: #003f59
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
		<h1>Venue</h1>
		<ol class="breadcrumb">
			<li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
			<li><a href="active"> Venue</a></li>
		</ol>
	</section>
	<section class="content">
		<section class="content p-l-r-15 venueLoader">
			<div class="venueLoader preloader" style="background: none !important; ">
				<div class="loader_img">
					<img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
				</div>
			</div>
			<div class="row">
				<div class="panel panel-primary ">
					<div class="panel-heading">
						<h4 class="panel-title">    
							<i class="fa fa-fw fa-users"></i> Venue
						</h4>
					</div>
					<input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
					<div class="panel-body" style="background-color: #d9ecf5">
						<div class="row">
							<div class="col-md-12">
								<form method='POST' action='{!!url("venue")!!}' id="venueForm">
									{{ csrf_field() }}
									<div id="tabstrip">
										<ul>
											<li class="k-state-active">Venue</li>
											<li class="addVenueTab" style="padding: 2px"><i class="fa fa-plus addTab"></i></li>
										</ul>
										<div id="tabstrip-1">
											<div class="tab-content">
												<div class="row">
													<div class="form-group">
														<label for="venue_name" class="col-sm-2">Venue Name</label>
														<div class="col-sm-3">
															<input id="venue_name" name="postals[0][venue_name]" type="text" class="form-control k-textbox" required validationMessage="Venue Name Required">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label for="subpremise" class="col-sm-2">Address 1</label>
														<div class="col-sm-3">
															<input id="subpremise" name="postals[0][subpremise]" type="text" class="form-control k-textbox" required validationMessage="Address Required">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label for="premise" class="col-sm-2">Address 2</label>
														<div class="col-sm-3">
															<input id="premise" name="postals[0][premise]" type="text" class="form-control k-textbox" />
														</div>
														<div class="col=md-4">
															<button type="button" value="0" class="btn btn-success getLocation">Get Location</button>
														</div>
													</div>
												</div>
												<div class="row postal editvenue" style="display: none;">
													<div class="form-group">
														<label for="street_number1" class="col-sm-2">Street Number</label>
														<div class="col-sm-3">
															<input id="street_number1" style="width: 100%" />
														</div>
													</div>
												</div>
												<div class="row postal editvenue" style="display: none;">
													<div class="form-group">
														<label for="route1" class="col-sm-2">Route</label>
														<div class="col-sm-3">
															<input id="route1" style="width: 100%" />
														</div>
													</div>
												</div>
												<div class="row postal editvenue" style="display: none;">
													<div class="form-group">
														<label for="neighborhood1" class="col-sm-2">Neighborhood</label>
														<div class="col-sm-3">
															<input style="width: 100%" id="neighborhood1" />
														</div>
													</div>
												</div>
												<div class="row postal-max" style="display: none;">
													<div class="form-group">
														<label for="postcode1" class="col-sm-2">Postcode</label>
														<div class="col-sm-3">
															<input style="width: 100%" id="postcode1" />
														</div>
													</div>
												</div>
												<div class="row postal editvenue" style="display: none;">
													<div class="form-group">
														<label for="lat" class="col-sm-2">Latitude</label>
														<div class="col-sm-3">
															<input type="text" class="form-control k-textbox" name="postals[0][lat]" id="lat" style="width: 100%" />
														</div>
													</div>
												</div>
												<div class="row postal editvenue" style="display: none;">
													<div class="form-group">
														<label for="lng" class="col-sm-2">Longitude</label>
														<div class="col-sm-3">
															<input type="text" class="form-control k-textbox" name="postals[0][lng]" id="lng" style="width: 100%" />
														</div>
													</div>
												</div>
												<div class="row postal editvenue" style="display: none;">
													<div class="form-group">
														<label for="country1" class="col-sm-2">Country</label>
														<div class="col-sm-3">
															<input name="postals[0][country]" id="country1" style="width: 100%" />
														</div>
													</div>
												</div>
												<div class="row postal editvenue" style="display: none;">
													<div class="form-group">
														<label for="state1" class="col-sm-2">State</label>
														<div class="col-sm-3">
															<input name="postals[0][state]" id="state1" style="width: 100%"/>
														</div>
													</div>
												</div>
												<div class="row postal editvenue" style="display: none;">
													<div class="form-group">
														<label for="county1" class="col-sm-2">County</label>
														<div class="col-sm-3">
															<input name="postals[0][county]" id="county1" style="width: 100%" />
														</div>
													</div>
												</div>
												<div class="row postal editvenue" style="display: none;">
													<div class="form-group">
														<label for="city1" class="col-sm-2">City</label>
														<div class="col-sm-3">
															<input name="postals[0][city]" id="city1" style="width: 100%" />
														</div>
													</div>
												</div>
												<input type="hidden" id="postalStreetNumber1" name="postals[0][street_number]" />
												<input type="hidden" id="postalRoute1" name="postals[0][route]" />
												<input type="hidden" id="postalNeighborhood1" name="postals[0][neighborhood]" />
												<input type="hidden" id="postalPostcode1" name="postals[0][postcode]" />
											</div>
										</div>
										<div style="display: none;"></div>
									</div>
									<div class="row">
										<div class="col-md-12 text-center" style="margin: 5px 0">
											<button type="button" id="venueSave" value="1" class="btn btn-success">Add Venues</button>	
										</div>
									</div>
								</form>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div id="venueGrid"></div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div id="demo" style="display: none;">
				<div class="tab-content">
					<div class="row">
						<div class="form-group">
							<label for="venue_name" class="col-sm-2">Venue Name</label>
							<div class="col-sm-3">
								<input id="venue_name" type="text" class="form-control k-textbox" required validationMessage="Venue Name Required">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<label for="subpremise" class="col-sm-2">Address 1</label>
							<div class="col-sm-3">
								<input id="subpremise" type="text" class="form-control k-textbox" required validationMessage="Address Required">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<label for="premise" class="col-sm-2">Address 2</label>
							<div class="col-sm-3">
								<input id="premise" type="text" class="form-control k-textbox" />
							</div>
							<div class="col=md-4">
								<button type="button" value="0" class="btn btn-success getLocation">Get Location</button>
							</div>
						</div>
					</div>
					<div class="row postal" style="display: none;">
						<div class="form-group">
							<label for="street_number" class="col-sm-2">Street Number</label>
							<div class="col-sm-3">
								<input id="street_number" style="width: 100%" />
							</div>
						</div>
					</div>
					<div class="row postal" style="display: none;">
						<div class="form-group">
							<label for="route" class="col-sm-2">Route</label>
							<div class="col-sm-3">
								<input id="route" style="width: 100%" />
							</div>
						</div>
					</div>
					<div class="row postal" style="display: none;">
						<div class="form-group">
							<label for="neighborhood" class="col-sm-2">Neighborhood</label>
							<div class="col-sm-3">
								<input id="neighborhood" style="width: 100%"  />
							</div>
						</div>
					</div>
					<div class="row postal-max" style="display: none;">
						<div class="form-group">
							<label for="postcode" class="col-sm-2">Postcode</label>
							<div class="col-sm-3">
								<input id="postcode" style="width: 100%"  />
							</div>
						</div>
					</div>
					<div class="row postal" style="display: none;">
						<div class="form-group">
							<label for="lat" class="col-sm-2">Latitude</label>
							<div class="col-sm-3">
								<input  type="text" id="lat" class="form-control k-textbox"  style="width: 100%" />
							</div>
						</div>
					</div>
					<div class="row postal" style="display: none;">
						<div class="form-group">
							<label for="lng" class="col-sm-2">Longitude</label>
							<div class="col-sm-3">
								<input type="text" id="lng" class="form-control k-textbox" style="width: 100%" />
							</div>
						</div>
					</div>
					<div class="row postal" style="display: none;">
						<div class="form-group">
							<label for="country" class="col-sm-2">Country</label>
							<div class="col-sm-3">
								<input id="country" style="width: 100%" />
							</div>
						</div>
					</div>
					<div class="row postal" style="display: none;">
						<div class="form-group">
							<label for="state" class="col-sm-2">State</label>
							<div class="col-sm-3">
								<input id="state" style="width: 100%"/>
							</div>
						</div>
					</div>
					<div class="row postal" style="display: none;">
						<div class="form-group">
							<label for="county" class="col-sm-2">County</label>
							<div class="col-sm-3">
								<input id="county" style="width: 100%" />
							</div>
						</div>
					</div>
					<div class="row postal" style="display: none;">
						<div class="form-group">
							<label for="city" class="col-sm-2">City</label>
							<div class="col-sm-3">
								<input id="city" style="width: 100%" />
							</div>
						</div>
					</div>
					<input type="hidden" id="postalStreetNumber" />
					<input type="hidden" id="postalRoute" />
					<input type="hidden" id="postalNeighborhood" />
					<input type="hidden" id="postalPostcode" />
				</div>
			</div>
		</section>
	</section>
@endsection
<script src="{{asset('assets/kendoui/js/jquery.min.js')}}" type="text/javascript"></script>
@section('footer_scripts')
	<script src="{{asset('assets/kendoui/js/kendo.all.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('assets/js/custom_js/VenueIndex.js')}}"></script>
@stop