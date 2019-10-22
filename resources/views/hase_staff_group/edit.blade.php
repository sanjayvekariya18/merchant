@extends('layouts/default')

{{-- Page title --}}
@section('title')
	Roles
	@parent
@stop

{{-- page level styles --}}
@section('header_styles')
	<!--page level css -->   
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">

	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>

	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
	<h1>edit Roles</h1>
	<ol class="breadcrumb">
		<li>
			<a href="index ">
				<i class="fa fa-fw fa-home"></i> Dashboard
			</a>
		</li>
		<li>
			<a href="#"> Users</a>
		</li>
		<li class="active">
			Roles
		</li>
	</ol>
</section>
<section class="content">
   <form id="staffgroupForm" method = 'POST' action = '{!!url("hase_staff_group")!!}/{!!$hase_staff_group->group_id!!}/update' class="form-horizontal" role="form">
		<input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
		<input type = 'hidden' name = 'staff_group_id' value='{!!$hase_staff_group->group_id!!}'>
		<div class="row">
			<div class="col-md-12">
				<button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
				<button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
				<a href="{!!url("hase_staff_group")!!}" class='btn btn-primary btn-inline'>
					<i class="fa fa-fw fa-arrow-left"></i>
				</a>
			</div>
		</div>
		<br/>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">
							<i class="fa fa-fw fa-crosshairs"></i> Roles
						</h3>
					</div>
					<div class="panel-body">
						<input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
						<div class="bs-example">
							<ul class="nav nav-tabs" style="margin-bottom: 15px;">
								<li class="active">
									<a href="#staff_groups" data-toggle="tab">Roles</a>
								</li>
								<li>
									<a href="#privilages" data-toggle="tab">Privileges</a>
								</li>
							</ul>
							<div id="myTabContent" class="tab-content">
								<div class="tab-pane fade active in" id="staff_groups">
									<?php if(Session('merchantId') == 0): ?>
			                            <div class="form-group">
			                                <label for="Merchant" class="col-sm-2">
			                                    Merchants
			                                </label>
			                                <div class="col-sm-5">
			                                    <select name="merchant_id" id="merchant_id" class="form-control select21" style="width:100%">
			                                        @foreach($merchants as $merchant)
			                                            @if($merchant->merchant_id == $hase_staff_group->merchant_id)
			                                                <option value="{{$merchant->merchant_id}}" selected>{{$merchant->merchant_name}}</option>
			                                            @else
			                                                <option value="{{$merchant->merchant_id}}">{{$merchant->merchant_name}}</option>
			                                            @endif
			                                        @endforeach
			                                    </select>
			                                </div>
			                            </div>
			                        <?php endif; ?>
									<div class="form-group">
										<label for="Name" class="col-sm-2">Name</label>
										<div class="col-sm-5">
											<input id="staff_group_name" name="staff_group_name" type="text" class="form-control" value="{!!$hase_staff_group->group_name!!}">
										</div>
									</div>
									<div class="form-group">
										<label for="staffGroupStatus" class="col-sm-2">
											Status
										</label>
										<div class="col-sm-5">
											<div class="make-switch" data-on="danger" data-off="default">
												@if($hase_staff_group->
													status)
													<input value="{!!$hase_staff_group->
													status!!}" id="staff_group_status" name="staff_group_status" type="checkbox" checked="true" />
												@else
													<input value="{!!$hase_staff_group->
													status!!}" id="staff_group_status" name="staff_group_status" type="checkbox" />
												@endif
											</div>
										</div>
									</div>
								</div>

								<div class="tab-pane fade" id="privilages">
									<div class="panel panel-default panel-table">
										<div class="table-responsive">
											<table class="table table-striped table-border">
												<thead>
													<tr>
														<th><b>Admin</b></th>
														<th class="action text-center">
															<a class="clickable" onclick="var $checkbox = $('input[value*=\'access\']');$checkbox.prop('checked', !$checkbox[0].checked);">Access
															</a>
														</th>
														<th class="action text-center success">
															<a class="clickable" onclick="var $checkbox = $('input[value*=\'manage\']');$checkbox.prop('checked', !$checkbox[0].checked);">Manage
															</a>
														</th>
														<th class="action text-center info">
															<a class="clickable" onclick="var $checkbox = $('input[value*=\'add\']');$checkbox.prop('checked', !$checkbox[0].checked);">Add
															</a>
														</th>
														<th class="action text-center danger">
															<a class="clickable" onclick="var $checkbox = $('input[value*=\'delete\']');$checkbox.prop('checked', !$checkbox[0].checked);">Delete
															</a>
														</th>
														<th>Description</th>
													</tr>
												</thead>
												<tbody>
													@foreach($hase_permissions as $hase_permission)
														@php $permissions = unserialize($hase_permission->action); @endphp
														<tr>
															<td>
																<a class="clickable" onclick="var $checkbox = $(this).parent().parent().find(':checkbox');$checkbox.prop('checked', !$checkbox[0].checked);">{!!$hase_permission->name!!}
																</a>
															</td>
															<td class="action text-center">
																@if(array_key_exists($hase_permission->name,$hase_staff_group->permissions) &&
																	in_array("access",$hase_staff_group->permissions[$hase_permission->name]))
																	<input name="permissions[{!!$hase_permission->name!!}][]" value="access"  type="checkbox" checked="true" />
																@elseif(in_array("access",$permissions))
																	<input name="permissions[{!!$hase_permission->name!!}][]" value="access" type="checkbox" />
																@else
																	--
																@endif
															</td>
															<td class="action text-center success">
																@if(array_key_exists($hase_permission->name,$hase_staff_group->permissions) &&
																	in_array("manage",$hase_staff_group->permissions[$hase_permission->name]))
																	<input type="checkbox" name="permissions[{!!$hase_permission->name!!}][]" value="manage" checked="true">
																@elseif(in_array("manage",$permissions))
																	<input type="checkbox" name="permissions[{!!$hase_permission->name!!}][]" value="manage">
																@else
																	--
																@endif
															</td>
															<td class="action text-center info">
																@if(array_key_exists($hase_permission->name,$hase_staff_group->permissions) &&
																	in_array("add",$hase_staff_group->permissions[$hase_permission->name]))
																	<input type="checkbox" name="permissions[{!!$hase_permission->name!!}][]" value="add" checked="true">
																@elseif(in_array("add",$permissions))
																	<input type="checkbox" name="permissions[{!!$hase_permission->name!!}][]" value="add">
																@else
																	--
																@endif
															</td>
															<td class="action text-center danger">
																@if(array_key_exists($hase_permission->name,$hase_staff_group->permissions) &&
																	in_array("delete",$hase_staff_group->permissions[$hase_permission->name]))
																	<input type="checkbox" name="permissions[{!!$hase_permission->name!!}][]" value="delete" checked="true">
																@elseif(in_array("delete",$permissions))
																	<input type="checkbox" name="permissions[{!!$hase_permission->name!!}][]" value="delete">
																@else
																	--
																@endif
															</td>
															<td>{!!$hase_permission->description!!}</td>
														</tr>
													@endforeach 
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
</section>
@endsection
{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->

<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>

<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseStaffGroupEdit.js')}}"></script>
<!-- end of page level js -->
<script type="text/javascript">
	
	@if(Session::has('type'))
		toastr.options = {
			"closeButton": true,
			"positionClass": "toast-top-right",
			"showDuration": "1000",
			"hideDuration": "1000",
			"timeOut": "5000",
			"extendedTimeOut": "1000",
			"showEasing": "swing",
			"hideEasing": "swing",
			"showMethod": "show"
		};
		var $toast = toastr["{{ Session::pull('type') }}"]("", "{{ Session::pull('msg') }}");
	@endif

</script>
@stop