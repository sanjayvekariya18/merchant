@extends('layouts/default')
{{-- Page title --}}
@section('title')
Regex
@parent
@stop
{{-- page level styles --}}
@section('header_styles')
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.common.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.rtl.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.default.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.mobile.all.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.blueopal.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
	<style>
		table thead tr{
			background-color: #d9ecf5;
			color: #003f59
		}
		#regex_setup table tr th,
		#regex_setup table tr td{
			width: 33%;
		}

		span.k-error{
			color: red;
		}
		.k-alert{
			top:250px !important;
			min-width: 200px !important;
		}
		#top_modal .form-group{
			margin: 5% 0;
		}
		.k-grid .k-grid-toolbar .k-grid-add,
		.k-grid tbody .k-grid-edit,
		.k-grid tbody .k-grid-update,
		.k-grid tbody .k-grid-cancel,
		.k-grid tbody .k-grid-delete {
			min-width: 0;
		}

		.k-grid .k-grid-toolbar .k-grid-add .k-icon,
		.k-grid tbody .k-grid-edit .k-icon,
		.k-grid tbody .k-grid-update .k-icon,
		.k-grid tbody .k-grid-cancel .k-icon,
		.k-grid tbody .k-grid-delete .k-icon {
			margin: 0;
		}
		.k-popup .k-item.k-first {
			border-width : 0 0 1px 0 !important;
		}
	</style>

@stop

@section('content')
	<section class="content-header">
		<h1>Regex</h1>
		<ol class="breadcrumb">
			<li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
			<li><a href="active"> Regex</a></li>
		</ol>
	</section>
	<section class="content">
		<section class="content p-l-r-15">
			<div class="row">
				<div class="panel panel-primary ">
					<div class="panel-heading">
						<h4 class="panel-title">
							<i class="fa fa-fw fa-users"></i> Regex
						</h4>
					</div>
					<input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
					<div class="panel-body" style="background-color: #d9ecf5">
						<div class="row">
							<div class="col-md-12">
								<div id="tabstrip">
									<ul>
										<li class="k-state-active">Primitives</li>
										<li class="">Lists</li>
										<li class="">Match</li>
										<li class="">Split</li>
									</ul>
									<div id="tab1">
										<div id="regexPrimitiveGrid"></div>
									</div>
									<div id="tab2">
										<div id="regexExtractGrid"></div>
									</div>
									<div id="tab3">
										<div id="regexSetupGrid"></div>
									</div>
									<div id="tab4">
										<div id="regexSplitGrid">	
											<form id="regexSplitForm" action="{!!url('regex')!!}/updateSplitData" method="post">
												{{csrf_field()}}
												<input type="hidden" name="splitData[split_id]" value=""/>		
												<div class="parents" style="margin-left: 15px;margin-top: 15px;">
													<div class="form-group" style="clear: both;">
														<div class="row">												
					                                        <div class="col-sm-12">
				                                                <div class="control-group group-margin">								                                                	
				                                                    <div class="input-group col-md-2" style="float: left;">
				                                                        <label for="" class="control-label">
									                                        Marker
									                                    </label>
				                                                    </div>
				                                                    <div class="input-group col-md-2" style="margin-left: 15px; float: left;">
				                                                        <label for="" class="control-label">
									                                        Node
									                                    </label>
				                                                    </div>				                                                    	                                                    
				                                                </div>
				                                            </div>
				                                        </div>    		                                            
			                                        </div>
			                                        <div class="parents_data">
			                                        	<div class="parent_0 form-group" style="clear: both;">
			                                        		<div class="row">												
						                                        <div class="col-sm-12">
					                                                <div class="control-group group-margin">								                                                	
					                                                    <div class="input-group col-md-2" style="float: left;">
					                                                        <input type="text" name="splitData[marker]" class="form-control" value="" placeholder="Marker"/>
					                                                    </div>
					                                                    <div class="input-group col-md-2" style="margin-left: 15px;float: left;">
					                                                        <input type="number" name="splitData[node]" class="form-control" value="" placeholder="Node Level"/>
					                                                    </div>
					                                                </div>
					                                            </div>
					                                        </div> 
					                                        <div class="child_data_header" style="margin-top:15px">
					                                        	<div class="row">
												                    <div class="col-sm-12">
												                        <div class="control-group group-margin">	
												                        	<div class="col-md-2" style="float: left;padding-right: 0px;"></div>							                                                	
												                            <div class="input-group col-md-2" style="float: left;">
												                                <label class="control-label">Regex Type</label>
												                            </div>
												                            <div class="input-group col-md-2" style="margin-left: 15px; float: left;">
												                                <label class="control-label">Delimiter</label>
												                            </div>		                            
											                                <div class="input-group col-md-2" style="margin-left: 15px; float: left;">
											                                    <label class="control-label">Variable Name</label>
											                                </div>	 
												                        </div>
												                    </div>
												                </div>
					                                        </div>
					                                        <div class="child_data" style="margin-top: 15px;">
					                                        	<div class="child_0">
													            	<div class="form-group" style="clear: both;">
													                    <div class="row">
														                    <div class="col-sm-12">
														                        <div class="control-group group-margin">	
														                        	<div class="col-md-2" style="float: left;padding-right: 0px;"></div>							                                                	
														                            <div class="input-group col-md-2" style="float: left;">
														                                <select name="splitData[child][0][type_id]" class="form-control select21 regextype">
																						    <option></option>
																						    @foreach($regexTypes as $regexType)
																						    	<option value="{!!$regexType->type_id!!}">{!!$regexType->type_name!!}</option>
																						    @endforeach	
																						</select>
														                            </div>
														                            <div class="input-group col-md-2" style="margin-left: 15px; float: left;">
														                                <input type="text" name="splitData[child][0][delimiter]" class="form-control delimiter" placeholder="delimiter"/>
														                            </div>		                            
													                                <div class="input-group col-md-2" style="margin-left: 15px; float: left;">
													                                    <input type="text" name="splitData[child][0][variable]" class="form-control variable" placeholder="variable"/>
													                                </div>	 
														                            <div class="input-group col-md-1" style="margin-left: 15px;float: left;">
													                                    <a class="btn btn-primary btn-lg addSplitter" data-parent="0" style="padding: 6px 5px;">
													                                        <i class="fa fa-plus"></i>
													                                    </a>
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
			                                    <div class="row" style="clear: both;padding: 15px;">
													<div class="col-sm-1">
														<button type="button" class="btn btn-primary btn-inline updateSplitData">Update</button>
													</div>
												</div>												
											</form>	
										</div>
										<div id="regexSplitHierarchyGrid"></div>	
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- form-modal -->
			<div id="top_modal" class="modal fade animated position_modal" data-backdrop="static" data-keyboard="false" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content" style="background-color: #d9ecf5">
						<div class="modal-header" style="background-color: #d9ecf5;">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Test Regex</h4>
						</div>

						<div class="panel panel-primary" style="border-color: snow;">
							<div class="panel-body" >
								<div class="preloader" style="background: none !important; ">
									<div class="loader_img">
										<img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
									</div>
								</div>
								<div class="row">
									<form id="testForm">
										{{ csrf_field() }}
										<div class="form-group">
											<label for="regex_pattern" class="col-sm-2">Pattern</label>
											<div class="col-sm-9">
												<input id="regex_pattern" name="regex_pattern" type="text" class="form-control" readonly="">
											</div>
										</div><br>
										<div class="form-group">
											<label for="test_url" class="col-sm-2">Test Url</label>
											<div class="col-sm-9">
												<input id="test_url" name="test_url" type="text" class="form-control" required="">
												<span class="k-error"></span>
											</div>
										</div><br>
										<div class="form-group">
											<label class="col-sm-2"></label>
											<div class="col-sm-9">
												<button type="button" class='btn btn-primary' id="testSubmit">Test Url</button>
											</div>
										</div>
									</form>
								</div><br>
								<div class="row resultBlock" style="display: none;">
									<div class="col-md-12">
										<div class="table-responsive">
											<table class="table table-bordered resultGrid" style='background:#fff'>
												<thead>
													<tr>
														<th>No#</th>
														<th>Result</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
									<div class="col-sm-2"></div>
									<div class="col-sm-8">
										<button type="button" value="accept" class="btn btn-success btn-inline action">Accept</button>
										<button type="button" value="reject" class="btn btn-danger action">Reject</button>
									</div>
									<div class="col-sm-2"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- form-modal end -->
		    <!-- Ref details-modal -->
			<div id="ref_details_top_modal" class="modal fade animated position_modal" role="dialog">
				<div class="modal-dialog" style="width: 60% !important">
	                <div class="modal-content" style="background-color: #d9ecf5">
	                    <div class="modal-header" style="background-color: #13688c;color: white">
	                        <button type="button" class="close" data-dismiss="modal">&times;</button>
	                        <h4 class="ref-details-modal-title">Lookup Details</h4>
	                    </div>
	                    <div class="panel panel-primary" style="border-color: snow;">
	                        <div class="panel-body">
	                            <form method='POST' id="refDetailsForm">
	                                {{ csrf_field() }}
	                                <div id="regexListGrid"></div>
	                            </form>
	                        </div>
	                    </div>
	                </div>
	            </div>
			</div>
			<!-- Ref details-modal end -->
			<!-- crosswalk details-modal -->
			<div id="crosswalk_details_top_modal" class="modal fade animated position_modal" role="dialog">
				<div class="modal-dialog" style="width: 60% !important">
	                <div class="modal-content" style="background-color: #d9ecf5">
	                    <div class="modal-header" style="background-color: #13688c;color: white">
	                        <button type="button" class="close" data-dismiss="modal">&times;</button>
	                        <h4 class="ref-details-modal-title">Crosswalk Details</h4>
	                    </div>
	                    <div class="panel panel-primary" style="border-color: snow;">
	                        <div class="panel-body">
	                            <form method='POST' id="crosswalkDetailsForm">
	                                {{ csrf_field() }}
	                                <div id="crosswalkGrid"></div>
	                            </form>
	                        </div>
	                    </div>
	                </div>
	            </div>
			</div>
			<!-- crosswalk details-modal end -->
			<!-- category window -->
	        <div id="category_top_modal" class="modal fade animated position_modal" role="dialog">
	            <div class="modal-dialog" style="width: 60% !important">
	                <div class="modal-content" style="background-color: #d9ecf5">
	                    <div class="modal-header" style="background-color: #13688c;color: white">
	                        <button type="button" class="close" data-dismiss="modal">&times;</button>
	                        <h4 class="category-modal-title">Category List</h4>
	                    </div>
	                    <div class="panel panel-primary" style="border-color: snow;">
	                        <div class="panel-body">
	                            <form method='POST' id="websiteCategoryForm">
	                                {{ csrf_field() }}
	                                <div id="websiteCategoryListGrid"></div>
	                            </form>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <!-- category window -->
	        <!-- reference table window -->
	        <div id="reference_top_modal" class="modal fade animated position_modal" role="dialog">
	            <div class="modal-dialog" style="width: 60% !important">
	                <div class="modal-content" style="background-color: #d9ecf5">
	                    <div class="modal-header" style="background-color: #13688c;color: white">
	                        <button type="button" class="close" data-dismiss="modal">&times;</button>
	                        <h4 class="reference-table-modal-title">Reference Table List</h4>
	                    </div>
	                    <div class="panel panel-primary" style="border-color: snow;">
	                        <div class="panel-body">
	                            <form method='POST' id="ReferenceTableForm">
	                                {{ csrf_field() }}
	                                <div id="regexTableAccessListGrid"></div>
	                            </form>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <!-- reference table window -->
	        <!-- regex type window -->
	        <div id="regex_type_top_modal" class="modal fade animated position_modal" role="dialog">
	            <div class="modal-dialog" style="width: 60% !important">
	                <div class="modal-content" style="background-color: #d9ecf5">
	                    <div class="modal-header" style="background-color: #13688c;color: white">
	                        <button type="button" class="close" data-dismiss="modal">&times;</button>
	                        <h4 class="regex-modal-title">Regex Type List</h4>
	                    </div>
	                    <div class="panel panel-primary" style="border-color: snow;">
	                        <div class="panel-body">
	                            <form id="websiteRegexForm">
	                                {{ csrf_field() }}
	                                <div id="websiteRegexTypeListGrid"></div>
	                            </form>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <!-- regex type window -->
	        <!-- regex field window -->
	        <div id="regex_field_top_modal" class="modal fade animated position_modal" role="dialog">
	            <div class="modal-dialog" style="width: 60% !important">
	                <div class="modal-content" style="background-color: #d9ecf5">
	                    <div class="modal-header" style="background-color: #13688c;color: white">
	                        <button type="button" class="close" data-dismiss="modal">&times;</button>
	                        <h4 class="regex-modal-title">Regex Field List</h4>
	                    </div>
	                    <div class="panel panel-primary" style="border-color: snow;">
	                        <div class="panel-body">
	                            <form method='POST' id="websiteRegexFieldForm">
	                                {{ csrf_field() }}
	                                <div id="websiteRegexFieldListGrid"></div>
	                            </form>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <!-- regex field window -->
	        
	        <!-- split row code-->
	        <div class="splitDemo" style="display: none">
	        	<div class="child">
	            	<div class="form-group" style="clear: both;">
	                    <div class="row">
		                    <div class="col-sm-12">
		                        <div class="control-group group-margin">	
		                        	<div class="col-md-2" style="float: left;padding-right: 0px;"></div>							                                                	
		                            <div class="input-group col-md-2" style="float: left;">
		                                <select name="" class="form-control regextype">
										    <option></option>
										    @foreach($regexTypes as $regexType)
										    	<option value="{!!$regexType->type_id!!}">{!!$regexType->type_name!!}</option>
										    @endforeach	
										</select>
		                            </div>
		                            <div class="input-group col-md-2" style="margin-left: 15px; float: left;">
		                                <input type="text" name="" class="form-control delimiter" placeholder="delimiter"/>
		                            </div>		                            
	                                <div class="input-group col-md-2" style="margin-left: 15px; float: left;">
	                                    <input type="text" name="" class="form-control variable" placeholder="variable"/>
	                                </div>	 
		                            <div class="input-group" style="margin-left:10px; float: left;">	
	                                	<a class="btn btn-danger" onclick="confirm('This can not be undone! Are you sure you want to do this?') ? $(this).parents().eq(4).remove() : false;" style="padding: 7px 7px; margin-left: 5px;">
	                                		<i class="fa fa-times-circle"></i>
	                                	</a>
	                                </div>                           
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
	<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('assets/js/custom_js/RegexSetup.js')}}"></script>
	<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
	<script id="crosswalkHeaderAction" type="text/x-kendo-template">
        <div class="searchToolBar" style="float: right;">
            <label class="search-label" for="searchBox">Search Grid:</label>
            <input type="search" id="crosswalkSearchBox" class="k-textbox" style="width: 250px"/>
            <input type="button" id="crosswalkBtnSearch" class="k-button" value="Search"/>
            <input type="button" id="crosswalkBtnReset" class="k-button" value="Reset"/>
        </div>
    </script>
    <script id="regexListHeaderAction" type="text/x-kendo-template">
        <div class="searchToolBar" style="float: right;">
            <label class="search-label" for="searchBox">Search Grid:</label>
            <input type="search" id="regexListSearchBox" class="k-textbox" style="width: 250px"/>
            <input type="button" id="regexListBtnSearch" class="k-button" value="Search"/>
            <input type="button" id="regexListBtnReset" class="k-button" value="Reset"/>
        </div>
    </script>    
	<script id="regexTypeSearch" type="text/x-kendo-template">
        <div class="searchToolBar" style="float: right;">
            <label class="search-label" for="searchBox">Search Grid:</label>
            <input type="search" id="RegexTypeSearchBox" class="k-textbox" style="width: 250px"/>
            <input type="button" id="RegexTypeBtnSearch" class="k-button" value="Search"/>
            <input type="button" id="RegexTypeBtnReset" class="k-button" value="Reset"/>
        </div>
    </script>
    <script id="regexFieldSearch" type="text/x-kendo-template">
        <div class="searchToolBar" style="float: right;">
            <label class="search-label" for="searchBox">Search Grid:</label>
            <input type="search" id="RegexFieldSearchBox" class="k-textbox" style="width: 250px"/>
            <input type="button" id="RegexFieldBtnSearch" class="k-button" value="Search"/>
            <input type="button" id="RegexFieldBtnReset" class="k-button" value="Reset"/>
        </div>
    </script>
     <script id="regexCategorySearch" type="text/x-kendo-template">
        <div class="searchToolBar" style="float: right;">
            <label class="search-label" for="searchBox">Search Grid:</label>
            <input type="search" id="RegexCategorySearchBox" class="k-textbox" style="width: 250px"/>
            <input type="button" id="RegexCategoryBtnSearch" class="k-button" value="Search"/>
            <input type="button" id="RegexCategoryBtnReset" class="k-button" value="Reset"/>
        </div>
    </script>
    <script id="regexRefTableSearch" type="text/x-kendo-template">
        <div class="searchToolBar" style="float: right;">
            <label class="search-label" for="searchBox">Search Grid:</label>
            <input type="search" id="RegexRefTableSearchBox" class="k-textbox" style="width: 250px"/>
            <input type="button" id="RegexRefTableBtnSearch" class="k-button" value="Search"/>
            <input type="button" id="RegexRefTableBtnReset" class="k-button" value="Reset"/>
        </div>
    </script>
@stop
