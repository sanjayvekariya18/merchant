@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Dynamic Menus
    @parent
@stop

@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/css/AdminLTE.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/bootstrap-slider/slider.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/kendoui/styles/kendo.common.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/kendoui/styles/kendo.mobile.all.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/kendoui/styles/kendo.blueopal.min.css')}}">

<style>
.btn-default{border-color:#D6D3D3}
.slider .tooltip{display:none !important;}
.slider.gray .slider-handle{background-color:#888;}
.slider.orange .slider-handle{background-color:#FF9800;}
.slider.green .slider-handle{background-color:#8BC34A;}

.guide1{text-align: right;margin: 0px 15px 15px 0px;font-size:16px;}
.guide1 .fa{font-size:22px;vertical-align:bottom;margin-left:17px;}
.guide1 .fa.gray{color:#888;}
.guide1 .fa.orange{color:#FF9800;}
.guide1 .fa.green{color:#8BC34A;}

.table-access{border:1px solid #CCC;}
.table-access thead tr{background-color: #DDD;}
.table-access thead tr th{border-bottom:1px solid #CCC;padding:10px 10px;text-align:center;}
.table-access thead tr th:first-child{text-align:left;}
.table-access input[type="checkbox"]{margin-right:5px;vertical-align:text-top;}
.table-access > tbody > tr > td{border-bottom:1px solid #EEE !important;padding:10px 10px;text-align:center;}
.table-access > tbody > tr > td:first-child {text-align:left;}

.table-access .tr-access-adv {background:#b9b9b9;}
.table-access .tr-access-adv .table{margin:0px;}
.table-access .tr-access-adv > td{padding: 7px 6px;}
.table-access .tr-access-adv .table-bordered td{padding:10px;}

.ui-field{list-style: none;padding: 3px 7px;border: solid 1px #cccccc;border-radius: 3px;background: #f5f5f5;margin-bottom: 4px;}
.actionIcons { float: left;margin: 1%;}
.profileIconMainDiv { padding-left: 2%; }
.moduleDetailsDiv { margin-left: 4%; }
.moduleStatus { margin: 0 !important;padding: 0 !important;cursor: text !important; }
.profile2 .dats1 { display: flex; }
.updateModuleText { float: right; }
.generateMessage { font-size: 16px;padding: 10px 16px; }
.actionColumnHeader { width: 13% !important;}
#groupPanelList .row{ margin-bottom: 5px }
.btn.btn-column-hide {
    color: #000000;
    background-color: #ffffff;
    border-color: #28A4C9 #28A4C9 #28A4C9;
}
.btn.btn-column-delete {
    color: #000000;
    background-color: #ffffff;
    border-color: #f55553 #f55553 #f55553;
}
</style>
@stop

@section('content')
<?php
use App\Helpers\LAHelper;
use App\Module;
?>
<div id="page-content" class="profile2">
	@if(isset($module->is_gen) && $module->is_gen)
	<div class="bg-success clearfix">
	@else
	<div class="bg-danger clearfix">
	@endif
		<div class="col-md-4">
			<div class="row">
				<div class="col-md-2 profileIconMainDiv">
					<div class="profile-icon text-primary"><i class="fa {{$module->fa_icon}}"></i></div>
				</div>
				<div class="col-md-9 moduleDetailsDiv">
					@if(isset($module->is_gen) && $module->is_gen)
						<a class="text-white" href="{{ url(''.$module->name_db) }}"><h4 data-toggle="tooltip" data-placement="left" title="Open {{ $module->model }} Module" class="name">{{ $module->label }}</h4></a>
					@else
						<h4>{{ $module->label }}</h4>
					@endif
					<div class="row stats">
						<div class="col-md-12">{{ Module::itemCount($module->name) }} Items</div>
					</div>
					<p class="desc">@if(isset($module->is_gen) && $module->is_gen) <div class="label2 success moduleStatus">Module Generated</div> @else <div class="label2 danger moduleStatus" style="border:solid 1px #FFF;">Module not Generated</div> @endif</p>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="dats1" data-toggle="tooltip" data-placement="left" title="Controller"><i class="fa fa-anchor"></i> {{ $module->controller }}</div>
			<div class="dats1" data-toggle="tooltip" data-placement="left" title="Model"><i class="fa fa-database"></i> {{ $module->model }}</div>
			<div class="dats1" data-toggle="tooltip" data-placement="left" title="View Column Name"><i class="fa fa-eye"></i>
				@if($module->view_col!="")
					{{$module->view_col}}
				@else
					Not Set
				@endif
			</div>
		</div>
		
		<div class="col-md-4">
			@if($module->view_col != "")
				@if(isset($module->is_gen) && $module->is_gen)
					<div class="dats1 text-right updateModuleText"><a data-toggle="tooltip" data-placement="left" title="Update Module" class="btn btn-sm btn-success" style="border-color:#FFF;" id="generate_update" crud_type_id=<?php echo $module->crud_type_id;?> href="#"><i class="fa fa-refresh"></i> Update Module</a></div>
					<?php if(Session::get('role')==1): ?>
						<div class="dats1 text-right updateModuleText"><a data-toggle="tooltip" data-placement="left" title="Update Migration File" class="btn btn-sm btn-success" style="border-color:#FFF;" id="update_migr" href="#"><i class="fa fa-database"></i> Update Migration</a></div>
					<?php endif; ?>
				@else
					<div class="dats1 text-right updateModuleText"><a data-toggle="modal" data-placement="left" data-target="#generate_migr_crud_model" title="Generate Migration + CRUD + Module" class="btn btn-sm btn-success" style="border-color:#FFF;"><i class="fa fa-cube"></i> Generate Module</a></div>
					<?php if(Session::get('role')==1): ?>
 							<div class="dats1 text-right updateModuleText"><a data-toggle="tooltip" data-placement="left" title="Generate Migration File" class="btn btn-sm btn-success" style="border-color:#FFF;" id="generate_migr" href="#"><i class="fa fa-database"></i> Generate Migration</a></div>
					<?php endif; ?>
				@endif
			@else
				<div class="generateMessage text-right">To generate Migration or CRUD, set the view column using the <i class='fa fa-eye'></i> icon next to a column</div>
			@endif
		</div>
		<?php if(in_array("delete", $permissions) && (Session::get('role')==1 || Module::itemCount($module->name) == 0)): ?>
			<div class="col-md-1 actions">
				<button module_name="{{ $module->name }}" module_id="{{ $module->id }}" class="btn btn-default btn-delete btn-xs delete_module"><i class="fa fa-times"></i></button>
			</div>
		<?php endif; ?>
		
	</div>

	<ul id="module-tabs" data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
		<li class=""><a href="{{ url('modules') }}" data-toggle="tooltip" data-placement="right" title="Back to Modules"> <i class="fa fa-chevron-left"></i>&nbsp;</a></li>
		
		<li class="tab-pane" id="fields">
			<a id="tab_fields" role="tab" data-toggle="tab" class="tab_info" href="#fields" data-target="#tab-info"><i class="fa fa-bars"></i> Module Fields</a>
		</li>
		
		<li class="tab-pane" id="access">
			<a id="tab_access" role="tab" data-toggle="tab"  class="tab_info " href="#access" data-target="#tab-access"><i class="fa fa-key"></i> Access</a>
		</li>
		
		<li class="tab-pane" id="sort">
			<a id="tab_sort" role="tab" data-toggle="tab"  class="tab_info " href="#sort" data-target="#tab-sort"><i class="fa fa-sort"></i> Sort</a>
		</li>
		<li class="tab-pane" id="gridAction">
			<a id="tab_grid_action" role="tab" data-toggle="tab"  class="tab_info " href="#gridAction" data-target="#tab-grid-action"><i class="fa fa-cog"></i> Grid Action</a>
		</li>
		<li class="tab-pane" id="groupBy">
			<a id="tab_grid_group_by" role="tab" data-toggle="tab"  class="tab_info " href="#gridGroupBy" data-target="#tab-grid-group-by"><i class="fa fa-group"></i> Group By</a>
		</li>
		<li class="tab-pane" id="groupBy">
			<a id="tab_grid_snippet" role="tab" data-toggle="tab"  class="tab_info " href="#gridSnippet" data-target="#tab-grid-snippet"><i class="fa fa-file"></i> Snippet</a>
		</li>
		
		<a data-toggle="modal" data-target="#AddFieldModal" class="btn btn-success btn-sm pull-right btn-add-field" style="margin-top:10px;margin-right:10px;">Add Field</a>
	</ul>

	<div class="tab-content">
		<div role="tabpanel" class="tab-pane fade in" id="tab-info">
			<div class="tab-content">
				<div class="panel">
					<!--<div class="panel-default panel-heading">
						<h4>Module Fields</h4>
					</div>-->
					<div class="panel-body">
						<a class="btn btn-info btn-column-hide btn-sm pull-left btn-add-field field_Action_submit" fieldActionData="hide" style="margin-right:4px;margin-bottom:4px;"></span><i class="fa fa-minus-circle"></i> Hide(<span class="selectedRowCount">0</span>)</a>
						<a class="btn btn-danger btn-column-delete btn-sm pull-left btn-add-field field_Action_submit" fieldActionData="delete" style="margin-right:4px;margin-bottom:4px;"><span class="fa fa-trash"> Delete(<span class="selectedRowCount">0</span>)</a>
						<input type="hidden" name="selectedFieldsName" id="selectedFieldsName" value="">
						<table id="dt_module_fields" class="table table-bordered" style="width:100% !important;">
						<thead>
						<tr class="success">
							<th style="display:none;"></th>
							<th><input type="checkbox" id="checkParent" /></th>
							<th>#</th>
							<th>Label</th>
							<th>Column</th>
							<th>Type</th>
							<th>Unique</th>
							<th>Default</th>
							<th>Min</th>
							<th>Max</th>
							<th>Required</th>
							<th>Values</th>
							<th class="actionColumnHeader"><i class="fa fa-cogs"></i></th>
						</tr>
						</thead>
						<tbody>														
							@foreach ($module->fields as $field)
								@if($field['visibility'] == 1)
									<tr>
								@else
									<tr bgcolor="silver">
								@endif
									<td style="display:none;">{{ $field['sort'] }}</td>
									<td><input type="checkbox" name="fieldCheckbox[]" class="checkChild" field_name="{{ $field['colname'] }}" field_id="{{ $field['id'] }}" /></td>
									<td>{{ $field['id'] }}</td>
									<td>{{ $field['label'] }}</td>
									<td>{{ $field['colname'] }}</td>
									<td>{{ $ftypes[$field['field_type']] }}</td>
									<td>@if($field['unique']) <span class="text-danger">True</span>@endif </td>
									<td>{{ $field['defaultvalue'] }}</td>
									<td>{{ $field['minlength'] }}</td>
									<td>{{ $field['maxlength'] }}</td>
									<td>@if($field['required']) <span class="text-danger">True</span>@endif </td>
									<td><?php echo LAHelper::parseValues($field['popup_vals']) ?></td>
									<td>
										<a href="{{ url('module_fields/'.$field['id'].'/edit') }}" class="actionIcons btn btn-edit-field btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;" id="edit_{{ $field['colname'] }}"><i class="fa fa-edit"></i></a>
										<a href="{{ url('module_fields/'.$field['id'].'/delete') }}" class="actionIcons btn btn-edit-field btn-danger btn-xs" style="display:inline;padding:2px 5px 3px 5px;" id="delete_{{ $field['colname'] }}"><i class="fa fa-trash"></i></a>
										@if($field['colname'] != $module->view_col)
											<a href="{{ url('modules/'.$module->id.'/set_view_col/'.$field['colname']) }}" class="actionIcons btn btn-edit-field btn-success btn-xs" style="display:inline;padding:2px 5px 3px 5px;" id="view_col_{{ $field['colname'] }}"><i class="fa fa-link"></i></a>
												@if($field['visibility'] == 1)
													<a href="{{ url('module_fields/'.$field['id'].'/set_visibility/hide') }}" class="actionIcons btn btn-edit-field btn-primary btn-xs" style="display:inline;padding:2px 5px 3px 5px;" id="show_{{ $field['colname'] }}">
														<i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="Hide"></i>
													</a>
												@else
													<a href="{{ url('module_fields/'.$field['id'].'/set_visibility/show') }}" class="actionIcons btn btn-edit-field btn-primary btn-xs" style="display:inline;padding:2px 5px 3px 5px;" id="show_{{ $field['colname'] }}">
														<i class="fa fa-eye-slash" data-toggle="tooltip" data-placement="top" title="Show"></i>
													</a>
												@endif
											</a>
										@endif
									</td>
								</tr>
							@endforeach
						</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane fade in p20 bg-white" id="tab-access">
			
			<form action="{{ url('save_role_module_permissions/'.$module->id) }}" method="post">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<table class="table table-bordered dataTable no-footer table-access">
					<thead>
						<tr class="blockHeader">
							<th width="14%">
								<input class="alignTop" type="checkbox" id="role_select_all" >&nbsp; Roles
							</th>
							<th width="14%">
								<input type="checkbox" id="view_all" >&nbsp; View
							</th>
							<th width="14%">
								<input type="checkbox" id="create_all" >&nbsp; Create
							</th>
							<th width="14%">
								<input type="checkbox" id="edit_all" >&nbsp; Edit
							</th>
							<th width="14%">
								<input class="alignTop" type="checkbox" id="delete_all" >&nbsp; Delete
							</th>
						</tr>
					</thead>

					<?php
						foreach($roles as $role){
							if($role['id'] == 1){
								$isEnable = "disabled";
							}else{
								$isEnable = "";
							}
					?>		
						<tr class="tr-access-basic" role_id="{{ $role['id'] }}">
							<td>
								<?php 
								if($role['id'] == 1){
								?>
								<a class="role_checkb" type="checkbox" name="all" id="module_{{ $role['id'] }}"> {{ $role["name"] }} </a></td>
								<?php } else { ?>
								<input type="hidden" name="module[{{ $role['id'] }}]" value="{{$role['id']}}">	
								<a class="role_checkb" type="checkbox" name="all" id="module_{{ $role['id'] }}"  onclick="var $checkbox = $(this).parent().parent().find(':checkbox');$checkbox.prop('checked', !$checkbox[0].checked);"> {{ $role["name"] }} </a></td>
								<?php } ?>
							<td><input class="view_checkb" type="checkbox" name="module[{{ $role['id'] }}][access]" id="module_view_{{$role['id']}}" value="{{$role['id']}}" <?php if(in_array('access', $role['permissions'])) { echo 'checked="checked"'; echo $isEnable;} ?> ></td>

							<td><input class="create_checkb" type="checkbox" name="module[{{ $role['id'] }}][add]" id="module_create_{{$role['id']}}" value="{{$role['id']}}" <?php if(in_array('add', $role['permissions'])) { echo 'checked="checked"'; echo $isEnable;} ?> ></td>

							<td><input class="edit_checkb" type="checkbox" name="module[{{ $role['id'] }}][manage]" id="module_edit_{{$role['id']}}" value="{{$role['id']}}" <?php if(in_array('manage', $role['permissions'])) { echo 'checked="checked"'; echo $isEnable;} ?> ></td>

							<td><input class="delete_checkb" type="checkbox" name="module[{{ $role['id'] }}][delete]" id="module_delete_{{$role['id']}}" value="{{$role['id']}}" <?php if(in_array('delete', $role['permissions'])) { echo 'checked="checked"'; echo $isEnable;} ?> ></td>

						</tr>
						
					<?php } ?>
				</table>
				<center><input class="btn btn-success" type="submit" name="Save" value="Update"></center>
			</form>
		<!--<div class="text-right p30"><i class="fa fa-list-alt" style="font-size: 100px;"></i> <br> No posts to show</div>-->
		</div>
		<div role="tabpanel" class="tab-pane fade in p20 bg-white" id="tab-sort">
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-3">
					<ul id="sortable_module_fields">
						@foreach ($module->fields as $field)
							<li class="ui-field" field_id="{{ $field['id'] }}"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>{{ $field['label'] }}
								@if($field['colname'] == $module->view_col)
									<i class="fa fa-eye pull-right" style="margin-top:3px;"></i>
								@endif
							</li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane fade in p20 bg-white" id="tab-grid-action">
			<div class="tab-content" style="padding: 20px">
				<div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">    
                            <i class="fa fa-fw fa-users"></i> Grid Action Panel
                        </h4>
                    </div>
                    <div class="panel-body">
                    	<div class="form-group">
							<label for="field_type" class="col-sm-2 control-label">Allow Edit:</label>
							{{ Form::checkbox("moduleActionAllowEdit", "moduleActionAllowEdit", $module->allow_edit, array('data-on-text'=>'Enabled','data-off-text'=>"Disabled","data-on-color"=>"success","data-off-color"=>"danger","class"=>"switchActionEvents")) }}
						</div>
                		<div class="form-group">
							<label for="field_type" class="col-sm-2 control-label">Allow Delete:</label>
							{{ Form::checkbox("moduleActionAllowDelete", "moduleActionAllowDelete", $module->allow_delete, array('data-on-text'=>'Enabled','data-off-text'=>"Disabled","data-on-color"=>"success","data-off-color"=>"danger","class"=>"switchActionEvents")) }}
						</div>
						<div class="form-group" >
							<label for="field_type" class="col-sm-2 control-label">Popup Required:</label>
							{{ Form::checkbox("moduleActionPopup", "moduleActionPopup", $module->popup, array('data-on-text'=>'Enabled','data-off-text'=>"Disabled","data-on-color"=>"success","data-off-color"=>"danger","class"=>"switchActionEvents")) }}
						</div>
						<div class="form-group" >
							<label for="field_type" class="col-sm-2 control-label">Select All:</label>
							{{ Form::checkbox("moduleActionSelectAll", "moduleActionSelectAll", $module->select_all, array('data-on-text'=>'Enabled','data-off-text'=>"Disabled","data-on-color"=>"success","data-off-color"=>"danger","class"=>"switchActionEvents")) }}
						</div>
                    </div>
                </div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane fade in p20 bg-white" id="tab-grid-snippet">
			<div class="tab-content" style="padding: 20px">
				<div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">
							<i class="fa fa-fw fa-users"></i> Grid Snippet
                        </h4>
                    </div>
                    <div class="panel-body">
                    	<div id="modulesSnippetGrid"></div>
                    </div>
                </div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane fade in p20 bg-white" id="tab-grid-group-by">
			<div class="tab-content" style="padding: 20px">
				<div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">    
                            <i class="fa fa-fw fa-users"></i> Group By Columns
                        </h4>
                    </div>
                    <div class="panel-body">
                		<?php $groupFieldArray = $moduleGroupFieldList->toArray(); ?>
		                                	
                    	{{ Form::open(['action' => ['ModuleController@moduleGroupStore'], 'id' => 'module_group_by_form', 'method' => 'post', 'style'=>'display:inline']) }}
                    		<input type="hidden" name="groupModuleId" id="groupModuleId" value="{{$module->id}}">
	                    	<div class="form-group">
		                		<label for="Parent" class="col-sm-2">
		                            Group By : 
	                            </label>
		                        <div class="col-sm-6">
		                            <select name="group_by[]" id="group_by" class="form-control select2" multiple>
		                                @foreach($moduleFieldList as $fieldValue)
		                                	@if(in_array($fieldValue->id, array_column($groupFieldArray, 'field_id')))
		                                		<option value="{{$fieldValue->id}}" selected>{{$fieldValue->colname}}</option>
	                                		@else
	                                			<option value="{{$fieldValue->id}}">{{$fieldValue->colname}}</option>
                                			@endif
		                                @endforeach
		                            </select>
		                        </div>
		                    </div>
		                    <br>
		                    <br>
		                    <div class="row">
	                            <div class="col-md-12">
	                                <div class="panel panel-primary">
	                                    <div class="panel-heading">
	                                        <h3 class="panel-title">
	                                            Set Columns
	                                        </h3>
	                                    </div>
	                                    <div class="panel-body" id="groupPanelList">
	                                    	<div class="row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <label class="col-md-2">Column Name</label>
                                                        <label class="col-md-3">Sort Order</label>
                                                    </div>
                                                </div>
                                            </div>
                                            @foreach($moduleGroupFieldList as $fieldObject)
			                                    <div class="row" id="field_id_{{$fieldObject->field_id}}">
												    <div class="col-md-2">
												    	<label id="column_name">{{$fieldObject->colname}}</label>
												        <input type="hidden" id="column_id" name="fieldObject[{{$fieldObject->field_id}}][field_id]" value="{{$fieldObject->field_id}}">
												    </div>
												    <div class="col-sm-3">
												    	<select id="column_sort" name="fieldObject[{{$fieldObject->field_id}}][field_sort]" class="form-control" style="width: 100%">
												        	@foreach($sortOrder as $sortOrderKey=>$sortOrderValue)
												        		@if($fieldObject->sort == $sortOrderKey)
												                    <option value="{{$sortOrderKey}}" selected>{{$sortOrderValue}}</option>
										                    	@else
										                    		<option value="{{$sortOrderKey}}">{{$sortOrderValue}}</option>
									                    		@endif
												            @endforeach
											        	</select>
												    </div>
												</div>
			                                @endforeach
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
                        {!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!}
						{!! Form::close() !!}
                    </div>
                </div>
			</div>
		</div>
	</div>
	</div>
	</div>
</div>

<!-- module deletion confirmation  -->
<div class="modal" id="module_delete_confirm">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title">Module Delete</h4>
			</div>
			<div class="modal-body">
				<p>Do you really want to delete module <b id="moduleNameStr" class="text-danger"></b> ?</p>
				<p>Following files will be deleted:</p>
				<div id="moduleDeleteFiles"></div>
				<p class="text-danger">Note: Migration file will not be deleted but modified.</p>
			</div>
			<div class="modal-footer">
				{{ Form::open(['action' => ['MenuController@destroy', 0], 'id' => 'module_del_form', 'method' => 'delete', 'style'=>'display:inline']) }}
					<button class="btn btn-danger btn-delete pull-left" type="submit">Yes</button>
				{{ Form::close() }}
				<a data-dismiss="modal" class="btn btn-default pull-right" >No</a>				
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<div class="modal fade" id="AddFieldModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="table-select-content">
				<div class="preloader" style="background: none !important; ">
			        <div class="loader_img">
			            <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
			        </div>
			    </div>
			</div>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add {{ $module->model }} Field</h4>
			</div>
			{!! Form::open(['route' => 'module_fields.store', 'id' => 'field-form']) !!}
			{{ Form::hidden("module_id", $module->id) }}
			{{ Form::hidden("provider_id", $module->provider_id,array('id' => 'provider_id')) }}
			<div class="modal-body">
				<div class="box-body">
					<div class="form-group">
						<label for="label">Field Label :</label>
						{{ Form::text("label", null, ['class'=>'form-control', 'placeholder'=>'Field Label', 'data-rule-minlength' => 2, 'data-rule-maxlength'=>20, 'required' => 'required']) }}
					</div>
					
					<div class="form-group">
						<label for="colname">Column Name :</label>
						{{ Form::text("colname", null, ['class'=>'form-control', 'placeholder'=>'Column Name (lowercase)', 'data-rule-minlength' => 2, 'data-rule-maxlength'=>20, 'data-rule-banned-words' => 'true', 'required' => 'required']) }}
					</div>
					
					<div class="form-group">
						<label for="field_type">UI Type:</label>
						{{ Form::select("field_type", $ftypes, null, ['class'=>'form-control', 'required' => 'required']) }}
					</div>
					<div id="unique_val">
						<div class="form-group">
							<label for="unique">Unique:</label>
							<div class="make-switch" data-on="danger" data-off="default">
	                           	{{ Form::checkbox("unique", "unique", false, array("class"=>"switchActionEvents")) }}
	                    	</div>
						</div>
					</div>	
					<div id="precision_value">
						<div class="form-group">
							<label for="precision_value">Precision:</label>
							{{ Form::number("precision_value", null, ['class'=>'form-control', 'placeholder'=>'precision Value','min'=>1,'max'=>8]) }}
						</div>
					</div>	
					
					<div id="default_val">
						<div class="form-group">
							<label for="defaultvalue">Default Value :</label>
							{{ Form::text("defaultvalue", null, ['class'=>'form-control', 'placeholder'=>'Default Value']) }}
						</div>
					</div>

					<div id="length_div">
						<div class="form-group">
							<label for="minlength">Minimum :</label>
							{{ Form::number("minlength", null, ['class'=>'form-control', 'placeholder'=>'Minimum Value']) }}
						</div>
						
						<div class="form-group">
							<label for="maxlength">Maximum :</label>
							{{ Form::number("maxlength", null, ['class'=>'form-control', 'placeholder'=>'Maximum Value']) }}
						</div>
					</div>
					
					<div class="form-group">
						<label for="required">Required:</label>
						<div class="make-switch" data-on="danger" data-off="default">
	                           	{{ Form::checkbox("required", "required", false, array("class"=>"switchActionEvents")) }}
	                    </div>
					</div>
					<div class="form-group">
						<label for="visibility">Visibility:</label>
						<div class="make-switch" data-on="danger" data-off="default">
	                           	{{ Form::checkbox("visibility", "visibility", false, array("class"=>"switchActionEvents","checked")) }}
	                    </div>
					</div>					
					<!--
					<div class="form-group">
						<label for="popup_vals">Values :</label>
						{{-- Form::text("popup_vals", null, ['class'=>'form-control', 'placeholder'=>'Popup Values (Only for Radio, Dropdown, Multiselect, Taginput)']) --}}
					</div>
					-->
					
					<div class="form-group values">
						<label for="popup_vals" style="padding-right: 25px;">Values :</label>
						<div class="radio" style="margin-bottom:20px;">
							<label style="padding-right: 25px;">{{ Form::radio("popup_value_type", "table", true) }} From Table</label>
							<label>{{ Form::radio("popup_value_type", "list", false) }} From List</label>
						</div>
						
						<div class="form-group popup_vals_table_data">
							{{ Form::select("popup_vals_table", $tables, "", ['id'=>'popup_vals_table', 'class'=>'form-control', 'rel' => '']) }}

							<label for="popup_field_id" style="margin-top: 10px;">Option Value Field</label>
							<select id="popup_field_id" name="popup_field_id" class="form-control">
								<option value="1">id</option>
								<option value="2">name</option>
							</select>
							
							<label for="popup_field_name" style="margin-top: 10px;">Option Text Field</label>
							<select id="popup_field_name" name="popup_field_name" class="form-control">
								<option value="1">id</option>
								<option value="2">name</option>
							</select>
						</div>
						<select id="popup_vals_list" class="form-control popup_vals_list" rel="taginput" multiple="1" data-placeholder="Add Multiple values (Press Enter to add)" name="popup_vals_list[]">
							@if(env('APP_ENV') == "testing")
								<option>Bloomsbury</option>
								<option>Marvel</option>
								<option>Universal</option>
							@endif
						</select>
					</div>
					
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>

<div class="modal fade" id="fm" role="dialog" aria-labelledby="fileManagerLabel">
	<input type="hidden" id="image_selecter_origin" value="">
	<input type="hidden" id="image_selecter_origin_type" value="">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="fileManagerLabel">Select File</h4>
			</div>
			<div class="modal-body p0">
				<div class="row">
					<div class="col-xs-3 col-sm-3 col-md-3">
						<div class="fm_folder_selector">
							<form action="{{ url('upload_files')}}" id="fm_dropzone" enctype="multipart/form-data" method="POST">
								{{ csrf_field() }}
								<div class="dz-message"><i class="fa fa-cloud-upload"></i><br>Drop files here to upload</div>
								
								@if(!config('app.uploads.private_uploads'))
									<label class="fm_folder_title">Is Public ?</label>
									{{ Form::checkbox("public", "public", config("app.uploads.default_public"), array("class"=>"switchActionEvents")) }}
									<div class="Switch Ajax Round On"><div class="Toggle"></div></div>
								@endif
							</form>
						</div>
					</div>
					<div class="col-xs-9 col-sm-9 col-md-9 pl0">
						<div class="nav">
							<div class="row">
								<div class="col-xs-2 col-sm-7 col-md-7"></div>
								<div class="col-xs-10 col-sm-5 col-md-5">
									<input type="search" class="form-control pull-right" placeholder="Search file name">
								</div>
							</div>
						</div>
						<div class="fm_file_selector">
							<ul>
								
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- CRUD generation Operation -->
<div class="modal" id="generate_migr_crud_model">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title">CRUD Selection</h4>
			</div>
			{{ Form::open(array('url' => 'module_generate_migr_crud','id' => 'field-form')) }}
			{{ Form::hidden("module_id", $module->id) }}
			<div class="modal-body">
				<div class="box-body">
					<div class="form-group">
						<label for="field_type">CRUD Type:</label>
						{{ Form::select("crud_id", $crudType, null, ['class'=>'form-control', 'required' => 'required']) }}
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!}
			</div>
			{!! Form::close() !!}
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<!-- field mass deletion confirmation  -->
<div class="modal" id="field_mass_delete_confirm">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title">Fields to <span class="fieldActionEvent"></b></h4>
			</div>
			<div class="modal-body">
				<p>Do you really want to <b id="fieldActionStr" class="text-danger fieldActionEvent"></b> these fields ?</p>
				<p>Here is the Field List:</p>
				<div id="fieldDeleteList"></div>
			</div>
			<div class="modal-footer">
				<form id="massFieldActionForm" action="{{ url('module_fields/massFieldAction') }}" method="POST">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="massActionModuleId" id="massActionModuleId" value="{{$module->id}}">
					<input type="hidden" name="fieldAction" id="fieldAction" />
					<input type="hidden" name="selectedFields" id="selectedFields" />
					<button class="btn btn-danger btn-delete pull-left" type="submit">Yes</button>
				</form>
				<a data-dismiss="modal" class="btn btn-default pull-right" >No</a>				
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<div class="row" id="columnGroupByDemo" style="display: none;">
    <div class="col-md-2">
    	<label id="column_name"></label>
        <input type="hidden" id="column_id" name="column_id" />
    </div>
    <div class="col-sm-3">
        <select name="column_sort" id="column_sort" class="form-control" style="width: 100%">
        	@foreach($sortOrder as $sortOrderKey=>$sortOrderValue)
                    <option value="{{$sortOrderKey}}">{{$sortOrderValue}}</option>
            @endforeach
        </select>
    </div>
</div>
@endsection

@section('footer_scripts')

<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('la-assets/plugins/bootstrap-slider/bootstrap-slider.js') }}"></script>
<script src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
<script src="{{ asset('la-assets/plugins/jQueryUI/jquery-ui.js') }}"></script>
<script src="{{ asset('la-assets/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('la-assets/plugins/bootstrap-datetimepicker/moment.min.js') }}"></script>
<script src="{{ asset('la-assets/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js') }}"></script>
<script src="{{ asset('la-assets/plugins/stickytabs/jquery.stickytabs.js') }}"></script>
<script src="{{ asset('la-assets/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('la-assets/plugins/dropzone/dropzone.js') }}"></script>
<script src="{{ asset('la-assets/js/app.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/TosterMessage/TosterMessage.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/BootstrapSwitch/BootstrapSwitch.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}"></script>
<script>

$(function () {
	
	var module_id = <?php echo $module->id; ?>;
	$(".switchActionEvents").on('switchChange.bootstrapSwitch', function (event, state) {
		var actionName = $(this).attr("name");
		$.ajax({
			url: "{{ url('moduleActionStore') }}/"+ module_id,
			data : {'state': state,'actionName':actionName},
			method: 'POST',
			headers: {
		    	'X-CSRF-Token': '{{ csrf_token() }}'
    		},
			success: function( data ) {
				displayTosterMessage(data.type,data.message);
			}
		});
	    event.preventDefault();
	});
	$("#group_by").select2({
        theme: "bootstrap",
        placeholder: "Select Columns",
    });
	$('#checkParent').click(function() {
		var isChecked = $(this).prop("checked");
		$('.checkChild').prop('checked', isChecked);
		var selectedFieldId = $('.checkChild:checked').map(function() {return $(this).attr('field_id');}).get().join(',')
		var selectedFieldName = $('.checkChild:checked').map(function() {return $(this).attr('field_name');}).get().join(',')
		$('#selectedFields').val(selectedFieldId);
		$('#selectedFieldsName').val(selectedFieldName);
		var selectedRowCount = $('.checkChild:checked').length;
		$('.selectedRowCount').html(selectedRowCount);
	});

	$('.checkChild').click(function() {
	    var isChecked = $(this).prop("checked");
	    var isHeaderChecked = $("#checkParent").prop("checked");
	    if (isChecked == false && isHeaderChecked)
	        $("#checkParent").prop('checked', isChecked);
	    else {
	        $('.checkChild').each(function() {
	            if ($(this).prop("checked") == false)
	            isChecked = false;
	        });
	        $("#checkParent").prop('checked', isChecked);
	    }
	    var selectedFieldId = $('.checkChild:checked').map(function() {return $(this).attr('field_id');}).get().join(',')
		var selecteFieldName = $('.checkChild:checked').map(function() {return $(this).attr('field_name');}).get().join(',')
		$('#selectedFields').val(selectedFieldId);
		$('#selectedFieldsName').val(selecteFieldName);
		var selectedRowCount = $('.checkChild:checked').length;
		$('.selectedRowCount').html(selectedRowCount);
	});

	$('.field_Action_submit').on("click", function (eventObject) {

		var selectedAction = $(this).attr("fieldActionData");
		$(".fieldActionEvent").html(selectedAction);
		$("#fieldAction").val(selectedAction);
	    // Get the Login Name value and trim it
	    var selecteFields = $.trim($('#selectedFields').val());

	    // Check if empty of not
	    if (selecteFields  === '') {
	        displayTosterMessage('error','Please select at least one column.');
	        return false;
	    }

		var field_names = $("#selectedFieldsName").val();
		$("#field_mass_delete_confirm").modal('show');
		var fieldNameArray = field_names.split(',');
		var fieldList = "<ul>";
		for (moduleIndex = 0; moduleIndex < fieldNameArray.length; moduleIndex++) { 
			fieldList += "<li>" + fieldNameArray[moduleIndex] + "</li>";
		}
		fieldList += "</ul>";
		$("#fieldDeleteList").html(fieldList);

	});

	$("select.popup_vals_list").show();
	$("select.popup_vals_list").next().show();
	$("select[name='popup_vals']").hide();

	$('.delete_module').on("click", function () {
    	var module_id = $(this).attr('module_id');
		var module_name = $(this).attr('module_name');
		$("#moduleNameStr").html(module_name);

		$url = $("#module_del_form").attr("action");
		$("#module_del_form").attr("action", $url.replace("/0", "/"+module_id));
		$("#module_delete_confirm").modal('show');
		

		$.ajax({
			url: "{{ url('get_module_files/') }}/" + module_id,
			type:"GET",
			beforeSend: function() {
				$("#moduleDeleteFiles").html('<center><i class="fa fa-refresh fa-spin"></i></center>');
			},
			headers: {
		    	'X-CSRF-Token': '{{ csrf_token() }}'
    		},
			success: function(data) {
				var files = data.files;
				var filesList = "<ul>";
				for ($i = 0; $i < files.length; $i++) { 
					filesList += "<li>" + files[$i] + "</li>";
				}
				filesList += "</ul>";
				$("#moduleDeleteFiles").html(filesList);
			}
		});
	});
	
	function showValuesSection() {
		var ft_val = $("select[name='field_type']").val();
		if(ft_val == 7 || ft_val == 15 || ft_val == 18 || ft_val == 20 || ft_val == 25) {
			$(".form-group.values").show();
		} else {
			$(".form-group.values").hide();
		}
				
		$('#length_div').removeClass("hide");
		if(ft_val == 2 || ft_val == 4 || ft_val == 5 || ft_val == 7 || ft_val == 9 || ft_val == 11 || ft_val == 12 || ft_val == 15 || ft_val == 18 || ft_val == 21 || ft_val == 24 || ft_val == 25 ) {
			$('#length_div').addClass("hide");
		}

		$('#unique_val').removeClass("hide");
		if(ft_val == 1 || ft_val == 2 || ft_val == 3 || ft_val == 7 || ft_val == 9 || ft_val == 11 || ft_val == 12 || ft_val == 15 || ft_val == 18 || ft_val == 20 || ft_val == 21 || ft_val == 24 || ft_val == 25 ) {
			$('#unique_val').addClass("hide");
		}

		$('#default_val').removeClass("hide");
		if(ft_val == 11) {
			$('#default_val').addClass("hide");
		}

		$('#precision_value').addClass("hide");
		if(ft_val == 3)
		{
			$('#precision_value').removeClass("hide");
			$('#precision_value').addClass("show");
		}
	}

	$("select[name='field_type']").on("change", function() {
		showValuesSection();
	});
	showValuesSection();

	function showValuesTypes() {
		console.log($("input[name='popup_value_type']:checked").val());
		if($("input[name='popup_value_type']:checked").val() == "list") {
			$("select.popup_vals_list").show();
			$("select.popup_vals_list").next().show();
			$(".popup_vals_table_data").hide();
		} else {
			$(".popup_vals_table_data").show();
			$("select.popup_vals_list").hide();
			$("select.popup_vals_list").next().hide();
		}
	}

	$("#popup_vals_table").on("change",function(){
		var schema_name = $(this).val();
		var providerId  = $("#provider_id").val();
		$('.table-select-content .preloader').show();
        $('.table-select-content .preloader img').show();
		$.ajax({
			url: "{{ url('get_schema_fields') }}",
			method: 'POST',
			data: {'schema_name':schema_name,'provider_id':providerId},
			headers: {
		    	'X-CSRF-Token': '{{ csrf_token() }}'
    		},
			success: function(data){
				data = $.parseJSON(data);
				optionList = "";
				for(var field in data) {
   					optionList += "<option value='" + data[field] + "'>" + data[field] + "</option>";
				}			
				$("#popup_field_id").html(optionList);
				$("#popup_field_name").html(optionList);
				$('.table-select-content .preloader').hide();
				$('.table-select-content .preloader img').hide();
			}
		});		
	});
	
	$("input[name='popup_value_type']").on("change", function() {
		showValuesTypes();
	});
	showValuesTypes();

	$("#sortable_module_fields").sortable({
		update: function(event, ui) {
            // var index = ui.placeholder.index();
            var array = [];
			$("#sortable_module_fields li").each(function(index) {
				var field_id = $(this).attr("field_id");
				if(typeof field_id != "undefined") {
					array.push(field_id);
				}
			});
			
			$.ajax({
				url: "{{ url('save_module_field_sort') }}/"+ module_id,
				data : {'sort_array': array},
				method: 'GET',
				success: function( data ) {
					displayTosterMessage(data.type,data.message);
				}
			});
        },
	});
    $("#sortable_module_fields").disableSelection();	
	
	$("#generate_migr").on("click", function() {
		var $fa = $(this).find("i");
		$fa.removeClass("fa-database");
		$fa.addClass("fa-refresh");
		$fa.addClass("fa-spin");
		$.ajax({
			url: "{{ url('module_generate_migr') }}/"+ module_id,
			method: 'GET',
			success: function( data ) {
				$fa.removeClass("fa-refresh");
				$fa.removeClass("fa-spin");
				$fa.addClass("fa-check");
				console.log(data);
				location.reload();
			}
		});
	});
	$("#update_migr").on("click", function() {
		var $fa = $(this).find("i");
		$fa.removeClass("fa-database");
		$fa.addClass("fa-refresh");
		$fa.addClass("fa-spin");
		$.ajax({
			url: "{{ url('module_generate_migr') }}/"+ module_id,
			method: 'GET',
			success: function( data ) {
				$fa.removeClass("fa-refresh");
				$fa.removeClass("fa-spin");
				$fa.addClass("fa-check");
				console.log(data);
				location.reload();
			}
		});
	});
	$("#generate_update").on("click", function() {
		var $fa = $(this).find("i");
		var crudTypeId = $(this).attr("crud_type_id");
		$fa.removeClass("fa-database");
		$fa.addClass("fa-refresh");
		$fa.addClass("fa-spin");
		$.ajax({
			url: "{{ url('module_generate_update') }}/"+ module_id+"/"+crudTypeId,
			method: 'GET',
			success: function( data ) {
				$fa.removeClass("fa-refresh");
				$fa.removeClass("fa-spin");
				$fa.addClass("fa-check");
				console.log(data);
				location.reload();
			}
		});
	});
	
	
	$.validator.addMethod("data-rule-banned-words", function(value) {
		return $.inArray(value, ['files']) == -1;
	}, "Column name not allowed.");

	$("#field-form").validate({
		rules: {
				defaultvalue: {
	    		required: function(element){
					return $("input[name='required']" ).is(":checked");
		        }
	        }
	    }
    });
		
	/* ================== Tab Selection ================== */
	
	var $tabs = $('#module-tabs').tabs();
	
	var url = window.location.href;
	var activeTab = url.substring(url.indexOf("#") + 1);
	
	if(!activeTab.includes("http") && activeTab.length > 1) {
		$('#module-tabs #'+activeTab+' a').tab('show');
	} else {
		$('#module-tabs #fields a').tab('show');
	}
	
	/* ================== Access Control ================== */
	
	$('.slider').slider();
	
	$(".slider.slider-horizontal").each(function(index) {
		var field = $(this).next().attr("name");
		var value = $(this).next().val();
		// console.log(""+field+" ^^^ "+value);
		switch (value) {
			case '0':
				$(this).removeClass("orange");
				$(this).removeClass("green");
				$(this).addClass("gray");
				break;
			case '1':
				$(this).removeClass("gray");
				$(this).removeClass("green");
				$(this).addClass("orange");
				break;
			case '2':
				$(this).removeClass("gray");
				$(this).removeClass("orange");
				$(this).addClass("green");
				break;
		}
	});
	
	$('.slider').bind('slideStop', function(event) {
		if($(this).next().attr("name")) {
			var field = $(this).next().attr("name");
			var value = $(this).next().val();
			// console.log(""+field+" = "+value);
			if(value == 0) {
				$(this).removeClass("orange");
				$(this).removeClass("green");
				$(this).addClass("gray");
			} else if(value == 1) {
				$(this).removeClass("gray");
				$(this).removeClass("green");
				$(this).addClass("orange");
			} else if(value == 2) {
				$(this).removeClass("gray");
				$(this).removeClass("orange");
				$(this).addClass("green");
			}
		}
	});

	$("#role_select_all").on("change", function() {
		$(".role_checkb").prop('checked', this.checked);
		$(".view_checkb").prop('checked', this.checked);
		$(".edit_checkb").prop('checked', this.checked)
		$(".create_checkb").prop('checked', this.checked);
		$(".delete_checkb").prop('checked', this.checked);
		$("#role_select_all").prop('checked', this.checked);
		$("#view_all").prop('checked', this.checked);
		$("#create_all").prop('checked', this.checked);
		$("#edit_all").prop('checked', this.checked);
		$("#delete_all").prop('checked', this.checked);		
	});
	
	$("#view_all").on("change", function() {
		$(".view_checkb").prop('checked', this.checked);
	});

	$("#create_all").on("change", function() {
		$(".create_checkb").prop('checked', this.checked);
		if($('#create_all').is(':checked')){
			$(".role_checkb").prop('checked', this.checked);
			$(".view_checkb").prop('checked', this.checked);
			$("#view_all").prop('checked', this.checked);
		}
	});
	
	$("#edit_all").on("change", function() {
		$(".edit_checkb").prop('checked', this.checked);
		if($('#edit_all').is(':checked')){
			$(".role_checkb").prop('checked', this.checked);
			$(".view_checkb").prop('checked', this.checked);
			$("#view_all").prop('checked', this.checked);
		}
	});
	
	$("#delete_all").on("change", function() {
		$(".delete_checkb").prop('checked', this.checked);
		if($('#delete_all').is(':checked')){
			$(".role_checkb").prop('checked', this.checked);
			$(".view_checkb").prop('checked', this.checked);
			$("#view_all").prop('checked', this.checked);
		}
	});
	
	$(".hide_row").on("click", function() { 
		var val = $(this).attr( "role_id" );
		var $icon = $(".hide_row[role_id="+val+"] > i");
		if($('.module_fields_'+val).hasClass('hide')) {
			$('.module_fields_'+val).removeClass('hide');
			$icon.removeClass('fa-chevron-down');
			$icon.addClass('fa-chevron-up');
		} else {
			$('.module_fields_'+val).addClass('hide');
			$icon.removeClass('fa-chevron-up');
			$icon.addClass('fa-chevron-down');
		}
	});

	$("#popup_vals_table").trigger('change');

	$('#group_by').on("select2:select", function (eventObject) { 
        var fieldId = eventObject.params.data.id;
        var fieldName = eventObject.params.data.text;
        setGroupByGrid(fieldId,fieldName)
    });

    $('#group_by').on("select2:unselect", function (event) {
        fieldId =event.params.data.id;
        $("#groupPanelList").find("#field_id_"+fieldId).remove();
    });

    var modulesSnippetGrid = jQuery("#modulesSnippetGrid").kendoGrid({
        dataSource: {
            pageSize: 50,
            autoSync: true,
            transport : {
                read : {
                    data:{_token:'{{ csrf_token() }}'},
                    url: "{{ url('modules/getModulesSnippet') }}/"+ module_id,
                    dataType: "json",
                    type: "POST"
                },
                destroy: {
                	data:{_token:'{{ csrf_token() }}'},
                    url: "{{ url('modules/deleteModulesSnippet') }}",
                    dataType: "jsonp",
                    type: "POST"
                }
            },
            schema: {
                model: {
                    id:'id'
                }
            },
        },
        pageable: {
            refresh: true,
            pageSizes: [50, 100, 200]
        },
        
        noRecords: true,
        messages: {
            noRecords: "No event Found"
        },
        scrollable: true,
        sortable: false,
        reorderable: true,
        groupable: true,
        resizable: true,
        editable:"incell",
        columnMenu: true,
        columns: [{
			command:[{
				name: "destroy",
				text:" ",
			}],
			title: "&nbsp;",
			width: "80px"
		},{
			field:"id",
			title:"Snippet Id",
			hidden: true
		},{
			field:"module_id",
			title:"Module Id",
			hidden: true
		},{
			field:"placeholder",
			title:"Placeholder"
		},{
			field:"snippet",
			title:"Snippet"
		}],
		save: function(data) {
            if (data.values.placeholder) {
                placeHolder = data.values.placeholder;
            } else {
                placeHolder = data.model.placeholder;
            }
            if (data.values.snippet) {
                snippetFile = data.values.snippet;
            } else {
                snippetFile = data.model.snippet;
            }
            if (data.values.module_id) {
                moduleId = data.values.module_id;
            } else {
                moduleId = data.model.module_id;
            }
            jQuery.ajax({
                type: 'POST',
                headers: {
			    	'X-CSRF-Token': '{{ csrf_token() }}'
	    		},
                data: {
                    id: data.model.id,
                    place_holder: placeHolder,
                    snippet_file: snippetFile,
                    module_id: moduleId,
                },
                url: "{{ url('modules/updateModulesSnippet') }}",
                success: function(moduleSnippetDetails) {
                    data.sender.dataSource.read();
		            response = moduleSnippetDetails.type;
		            if(response.localeCompare("success") == 0){
		                message = "Module Snippet Successfully Updated";
		            }else if (response.localeCompare("error") == 0){
		                message = moduleSnippetDetails.message;
		            }
		            displayTosterMessage(response,message);
		            var modulesSnippetGrid = $("#modulesSnippetGrid").data("kendoGrid");
                    modulesSnippetGrid.dataSource.read();
                }
            });
        },

    });
});

function setGroupByGrid(fieldId,fieldName)
{
	var columnGroupRow = $('#columnGroupByDemo').clone();
    columnGroupRow.removeAttr('id');
    columnGroupRow.removeAttr('style');
    columnGroupRow.attr('id',"field_id_"+fieldId);
    columnGroupRow.find('#column_name').text(fieldName);
    columnGroupRow.find('#column_id').attr('name',"fieldObject["+fieldId+"][field_id]").val(fieldId);
    columnGroupRow.find('#column_sort').attr('name',"fieldObject["+fieldId+"][field_sort]");
    $("#groupPanelList").append(columnGroupRow);
}
</script>
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
        var $toast = toastr["{{ Session::get('type') }}"]("", "{{ Session::get('msg') }}");
    @endif

</script>
@stop
