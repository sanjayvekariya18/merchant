@extends('layouts/default')
{{-- Page title --}}
@section('title')
	Customers
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
<link rel="stylesheet" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">

<style type="text/css">

	.panel-body label{
		font-weight: bold;
	}
	.panel-body div.form-group{
		background-color: #d9ecf5;
	    padding: 15px 10px;
	}
</style>

@section('content')
<section class="content-header">
	<h1>Customer</h1>
	  <ol class="breadcrumb">
		  <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
		  <li><a href="#"> Customer</a></li>
	  </ol>
</section>
<section class="content">
    <?php if(in_array("add", $permissions)): ?>
    	<form class = 'col s3' method = 'get' action = '{!!url("hase_customer")!!}/create'>
            <button class = 'btn btn-primary' type = 'submit'>Create New customer</button>
        </form>
    <?php endif; ?>
	<section class="content p-l-r-15">
		<div class="row">
			<div class="panel panel-primary ">
				<div class="panel-heading">
					<h4 class="panel-title">    
						<i class="fa fa-fw fa-users"></i> Customer
					</h4>
				</div>
				<input type="hidden" id="request_url" value="{{url('hase_customer')}}">
				{{ csrf_field() }}
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<div id="customerGrid"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- form-modal -->
        <div id="top_modal" class="modal fade animated position_modal" role="dialog">
            <div class="modal-dialog" style="width: 900px !important;">
                <div class="modal-content"  style="width: 900px !important;">
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
                                                    <td id="countryName" width="25%">
                                                        <select name="location_country" id="location_country" class="select21 form-control">
                                                            <option></option>
                                                            @foreach($hase_countries as $hase_country)
                                                                @if($hase_country['country_id'] == 94)
                                                                    <option value="{{$hase_country['country_id']}}" data-name="{{$hase_country['country_name']}}" selected="selected">{{$hase_country['country_name']}}</option>
                                                                @else
                                                                    <option value="{{$hase_country['country_id']}}" data-name="{{$hase_country['country_name']}}">{{$hase_country['country_name']}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td id="stateName" width="25%">
                                                        <select name="location_state" id="location_state" class="select21 form-control myState">
                                                            <option></option> 
                                                            @foreach($hase_states as $hase_state)
                                                                <option value="{{$hase_state['state_id']}}" data-name="{{$hase_state['state_name']}}">{{$hase_state['state_name']}}</option>
                                                            @endforeach               
                                                        </select>
                                                    </td>
                                                    <td id="countyName" width="25%">
                                                        <select name="location_county" id="location_county" class="select21 form-control">
                                                            <option></option>
                                                        </select>
                                                    </td>
                                                    <td id="cityName" width="25%">
                                                        <select name="location_city" id="location_city" class="select21 form-control">
                                                            <option></option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" style="text-align: center">
                                                        <button type="button" id="addLocation" value="1" class="btn btn-success" disabled>Add Location</button>
                                                    </td>
                                                </tr>    
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="tabstrip" style="line-height: 2.5;">
                                        <ul>
                                            <li class="k-state-active" style="display: none"></li>
                                        </ul>
                                        <div id="tabstrip-0" style="display: none"></div>
                                    </div>                                   
                                    <div class="modal-footer" style="text-align:center;">                                    	
                                        	<button type="button" id="updateLocation" value="1" class="btn btn-success">Update</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                                            Close
                                        </button>
                                    </div>   
                                    <input type="hidden" name="identity_id" id="identity_id"/>
                                    <input type="hidden" name="identity_table_id" id="identity_table_id">                                 
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="demo" style="display: none;">
            <div class="tab-content">
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
                                <td>
                                    <label id="country_label"></label> 
                                    <input id="country_id" type="hidden"/>
                                </td>
                                <td>
                                    <label id="state_label"></label> 
                                    <input id="state_id" type="hidden"/>
                                </td>
                                <td>
                                    <label id="county_label"></label> 
                                    <input id="county_id" type="hidden"/>
                                </td>
                                <td>
                                    <label id="city_label"></label> 
                                    <input id="city_id" type="hidden"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="subpremise" class="col-sm-2">Address 1</label>
                        <div class="col-sm-5">
                            <input id="subpremise" type="text" class="form-control k-textbox" required validationMessage="Address Required">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="premise" class="col-sm-2">Address 2</label>
                        <div class="col-sm-5">
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
                            <input id="neighborhood" style="width: 100%"  />
                        </div>
                    </div>
                </div>
                <div class="row postal-max" style="display: none;">
                    <div class="form-group">
                        <label for="postcode" class="col-sm-2">Postcode</label>
                        <div class="col-sm-5">
                            <input id="postcode" style="width: 100%"  />
                        </div>
                    </div>
                </div>
                <div class="row postal" style="display: none;">
                    <div class="form-group">
                        <label for="lat" class="col-sm-2">Latitude</label>
                        <div class="col-sm-5">
                            <input  type="text" id="lat" class="form-control k-textbox"  style="width: 100%" />
                        </div>
                    </div>
                </div>
                <div class="row postal" style="display: none;">
                    <div class="form-group">
                        <label for="lng" class="col-sm-2">Longitude</label>
                        <div class="col-sm-5">
                            <input type="text" id="lng" class="form-control k-textbox" style="width: 100%" />
                        </div>
                    </div>
                </div>
                <input type="hidden" id="postalStreetNumber" />
                <input type="hidden" id="postalRoute" />
                <input type="hidden" id="postalNeighborhood" />
                <input type="hidden" id="postalPostcode" />

                <input type="hidden" id="list_id"  value="" >
                <input type="hidden" id="postal_id" value="" >
            </div>
        </div>
	</section>
</section>
@endsection
<script src="{{asset('assets/kendoui/js/jquery.min.js')}}" type="text/javascript"></script>
@section('footer_scripts')
<script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseCustomerIndex.js')}}"></script>
<script type="text/javascript">
localStorage.setItem("userId","{{$user_id}}");
localStorage.setItem("accessibility","{{$accessibility}}");
</script>
@stop