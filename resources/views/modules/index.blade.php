@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Dynamic Menus
    @parent
@stop

@section('header_styles')
<link href="{{ asset('la-assets/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('la-assets/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('la-assets/css/ionicons.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.mobile.all.min.css">
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.blueopal.min.css">
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
<style type="text/css">
	.newModuleAdd
	{
		width: 95%;
	}
	.modal-dialog {
	    width: 830px;
	}
	.popupFieldDate{
		width: 100%;
	}
	.noColumnDisplay{
		text-align: center;
	}
	.popupActionClass
	{
		width: 20%;
		text-align: right;
	}
	.actionIcons 
	{ 
		float: left;
		margin: 1%;
	}
	.btn.btn-column-delete {
	    color: #000000;
	    background-color: #ffffff;
	    border-color: #f55553 #f55553 #f55553;
	}
	.dataTables_length
	{
		margin-top: 8%;
	    margin-left: 21%;
	    width: 40% !important;
	}
</style>
@stop

@section('content')
<?php
use App\Module;
?>
<section>

<div class="box box-success">
	<?php if(in_array("add", $permissions)): ?>
	<button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal" style="    margin: 10px;">Add Module</button>
	<?php endif; ?>
	<!--<div class="box-header"></div>-->
	<div class="box-body">
		<?php if(in_array("delete", $permissions)): ?>
			<a class="btn btn-danger btn-column-delete btn-sm btn-add-field module_Action_submit" fieldActionData="delete" style="margin-top: 40px;position: absolute;">
				<span class="fa fa-trash"> Delete(<span class="selectedRowCount">0</span>)
			</a>
		    <input type="hidden" name="selectedModulesName" id="selectedModulesName" value="">

		<?php endif; ?>
		<table id="dt_modules" class="table table-bordered">
		<thead>
		<tr class="success">
			<?php if(in_array("delete", $permissions)): ?>
				<th><input type="checkbox" id="checkParent" /></th>
			<?php endif; ?>
			<th>ID</th>
			<th>Name</th>
			<th>Table</th>
			<?php if(Session::get('role')==1): ?>
				<th>Owner</th>
				<th>Merchant</th>
			<?php endif; ?>
			<th>Items</th>
			<?php if(in_array("manage", $permissions) || in_array("delete", $permissions)):?>
			<th width="15%">Actions</th>
			<?php endif; ?>
		</tr>
		</thead>
		<tbody>
			
			@foreach ($moduleDetails as $module)
				<tr>
					<?php if(in_array("delete", $permissions) && (Session::get('role')==1 || Module::itemCount($module->name) == 0)): ?>
					<td><input type="checkbox" name="moduleCheckbox[]" class="checkChild" module_name="{{ $module->name }}" module_id="{{ $module->id }}" /></td>
					<?php else : ?>
						<td></td>
					<?php endif; ?>
					<td>{{ $module->id }}</td>
					<td><a href="{{ url('/modules/'.$module->id)}}">{{ $module->name }}</a></td>
					<td>{{ $module->name_db }}</td>
					<?php if(Session::get('role')==1): ?>
						<td>{{ $module->username }}</td>
						<td>{{ $module->identity_name }}</td>
					<?php endif; ?>
					<td>{{ Module::itemCount($module->name) }}</td>
					<?php if(in_array("manage", $permissions) || in_array("delete", $permissions)):?>
					<td>
						<?php if(in_array("manage", $permissions)): ?>
						<a href="{{ url('modules/'.$module->id)}}#fields" class="actionIcons btn btn-primary btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>
						<a href="{{ url('modules/'.$module->id)}}#access" class="actionIcons btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-key"></i></a>
						<a href="{{ url('modules/'.$module->id)}}#sort" class="actionIcons btn btn-success btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-sort"></i></a>
						<a href="{{ url('modules/'.$module->id)}}#gridAction" class="actionIcons btn btn-primary btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-cog"></i></a>
						<?php endif; ?>

                      	<?php if(in_array("delete", $permissions) && (Session::get('role')==1 || Module::itemCount($module->name) == 0)): ?>
						<a module_name="{{ $module->name }}" module_id="{{ $module->id }}" class="actionIcons btn btn-danger btn-xs delete_module" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-trash"></i></a>
						<?php endif; ?>
					</td>
					<?php endif; ?>
				</tr>
			@endforeach
		</tbody>
		</table>
	</div>
</div>

<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Module</h4>
			</div>
			<div class="modal-body">
				<div class="box-body">
					<div class="form-group">
						<label for="field_type">Provider:</label>
						{{ Form::select("provider_id", $databaseProvider, null, ['class'=>'form-control','id'=>'databaseProvider', 'required' => 'required']) }}

					</div>
					<div id="tabstrip">
		                <ul>
		                    <li class="k-state-active" >New Table</li>
		                    <li>Exist table</li>
		                </ul>
		                <div id="tab1">
		                	{!! Form::open(['action' => 'ModuleController@store', 'id' => 'module-add-form', 'class' => 'newModuleAdd']) !!}
		                	<input type="hidden" name="newTableProvider" id="newTableProvider">
							<div class="form-group">
								<label for="name">Module Name :</label>
								{{ Form::text("name", null, ['class'=>'form-control', 'placeholder'=>'Module Name', 'data-rule-minlength' => 2, 'data-rule-maxlength'=>20, 'required' => 'required']) }}
								<label id="message" style="color:red;display:none;">Only alphabet allowd</label>
							</div>
							<div class="form-group">
								<label for="icon">Icon</label>
								<div class="input-group">
									<input class="form-control" placeholder="FontAwesome Icon" name="icon" type="text" value="fa-cube"  data-rule-minlength="1" required>
									<span class="input-group-addon"></span>
								</div>
							</div>
							<div class="form-group">
								<label for="field_type" class="popupActionClass">Allow Edit:</label>
								{{ Form::checkbox("newTableAllowEditCommand", "newTableAllowEditCommand", false, array('data-on-text'=>'Enabled','data-off-text'=>"Disabled","data-on-color"=>"success","data-off-color"=>"danger","class"=>"switchActionEvents","checked")) }}
							</div>
							<div class="form-group">
								<label for="field_type" class="popupActionClass">Allow Delete:</label>
								{{ Form::checkbox("newTableAllowDeleteCommand", "newTableAllowDeleteCommand", false, array('data-on-text'=>'Enabled','data-off-text'=>"Disabled","data-on-color"=>"success","data-off-color"=>"danger","class"=>"switchActionEvents")) }}
							</div>
							<div class="form-group">
								<label for="field_type" class="popupActionClass">Popup Required:</label>
								{{ Form::checkbox("newTablePopupCommand", "newTablePopupCommand", false, array('data-on-text'=>'Enabled','data-off-text'=>"Disabled","data-on-color"=>"success","data-off-color"=>"danger","class"=>"switchActionEvents")) }}
							</div>
							<div class="form-group">
								<label for="field_type" class="popupActionClass">Select All:</label>
								{{ Form::checkbox("newTableSelectAllCommand", "newTableSelectAllCommand", false, array('data-on-text'=>'Enabled','data-off-text'=>"Disabled","data-on-color"=>"success","data-off-color"=>"danger","class"=>"switchActionEvents","checked")) }}
							</div>
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!}
							{!! Form::close() !!}
		                </div>
		                <div id="tab2">
		                	{!! Form::open(['action' => 'ModuleController@newModulestore', 'id' => 'module-exist-form', 'class' => 'newModuleAdd']) !!}
		                		<input type="hidden" name="existTableProvider" id="existTableProvider">
		                		<div class="form-group">
				                	<label for="field_type">Tables:</label>
									{{ Form::select("table_name", [], null, ['class'=>'form-control','id'=>'providerTableList', 'required' => 'required']) }}
								</div>
								<div class="form-group">
									<label for="icon">Popup Settings:</label>
									<table id="popupColumnTable" class="table table-bordered">
										<thead>
										<tr>
											<th>Field Name</th>
											<th>Popup Table</th>
											<th>Popup table ID</th>
											<th>Popup table Text</th>
										</tr>
										</thead>
										<tbody id="popupTableFields">
										</tbody>
									</table>
								</div>
								<div class="form-group">
									<label for="icon">Icon</label>
									<div class="input-group">
										<input class="form-control" placeholder="FontAwesome Icon" name="icon" type="text" value="fa-cube"  data-rule-minlength="1" required>
										<span class="input-group-addon"></span>
									</div>
								</div>
								<div class="form-group">
									<label for="field_type">Popup Required:</label>
									{{ Form::checkbox("existTablePopupCommand", "existTablePopupCommand", false, array('data-on-text'=>'Enabled','data-off-text'=>"Disabled","data-on-color"=>"success","data-off-color"=>"danger","class"=>"switchActionEvents")) }}
								</div>

								<div class="form-group">
									<label for="field_type">Allow Delete:</label>
									{{ Form::checkbox("existTableAllowDeleteCommand", "existTableAllowDeleteCommand", false, array('data-on-text'=>'Enabled','data-off-text'=>"Disabled","data-on-color"=>"success","data-off-color"=>"danger","class"=>"switchActionEvents")) }}
								</div>
								<div class="form-group">
									<label for="field_type">Allow Edit:</label>
									{{ Form::checkbox("existTableAllowEditCommand", "existTableAllowEditCommand", false, array('data-on-text'=>'Enabled','data-off-text'=>"Disabled","data-on-color"=>"success","data-off-color"=>"danger","class"=>"switchActionEvents","checked")) }}
								</div>
								<div class="form-group">
									<label for="field_type">Select All:</label>
									{{ Form::checkbox("existTableSelectAllCommand", "existTableSelectAllCommand", false, array('data-on-text'=>'Enabled','data-off-text'=>"Disabled","data-on-color"=>"success","data-off-color"=>"danger","class"=>"switchActionEvents","checked")) }}
								</div>
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!}
							{!! Form::close() !!}
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
				{{ Form::open(['route' => ['modules.destroy', 0], 'id' => 'module_del_form', 'method' => 'delete', 'style'=>'display:inline']) }}
					<button class="btn btn-danger btn-delete pull-left" type="submit">Yes</button>
				{{ Form::close() }}
				<a data-dismiss="modal" class="btn btn-default pull-right" >No</a>				
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<!-- module mass deletion confirmation  -->
<div class="modal" id="module_mass_delete_confirm">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title">Module Delete</h4>
			</div>
			<div class="modal-body">
				<p>Do you really want to delete these module ?</p>
				<p>Following modules will be deleted:</p>
				<div id="moduleDeleteList"></div>
				<p class="text-danger">Note: Migration file will not be deleted but modified.</p>
			</div>
			<div class="modal-footer">
				<form id="module_del_form" action="{{ url('modules/massModuleAction') }}" method="POST">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="selectedModules" id="selectedModules" value="">
					<button class="btn btn-danger btn-delete pull-left" type="submit">Yes</button>
				</form>
				<a data-dismiss="modal" class="btn btn-default pull-right" >No</a>				
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
</section>
@endsection
<script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
@section('footer_scripts')
<script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}"></script>
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('la-assets/plugins/iconpicker/fontawesome-iconpicker.js') }}"></script>
<script src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/TosterMessage/TosterMessage.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/BootstrapSwitch/BootstrapSwitch.js')}}"></script>
<script>
$(function () {

	$("#tabstrip").kendoTabStrip().data("kendoTabStrip");

    $('#checkParent').click(function() {
		var isChecked = $(this).prop("checked");
		$('.checkChild').prop('checked', isChecked);
		var selecteModuleId = $('.checkChild:checked').map(function() {return $(this).attr('module_id');}).get().join(',')
		var selecteModuleName = $('.checkChild:checked').map(function() {return $(this).attr('module_name');}).get().join(',')
		$('#selectedModules').val(selecteModuleId);
		$('#selectedModulesName').val(selecteModuleName);
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
	    var selecteModuleId = $('.checkChild:checked').map(function() {return $(this).attr('module_id');}).get().join(',')
		var selecteModuleName = $('.checkChild:checked').map(function() {return $(this).attr('module_name');}).get().join(',')
		$('#selectedModules').val(selecteModuleId);
		$('#selectedModulesName').val(selecteModuleName);
		var selectedRowCount = $('.checkChild:checked').length;
		$('.selectedRowCount').html(selectedRowCount);
	});

	$("#providerTableList").change(function() {
		$("#popupColumnTable tbody tr").remove();
		$('<tr>').append(
            $('<td class="noColumnDisplay" colspan="4">').text("No popup Field Define")).appendTo('#popupTableFields');
		
		var providerId = $("#databaseProvider").val();
		var tableName = $(this).val();
		$.ajax({
			url: "{{ url(Request::segment(1))}}/getSchemaTablePopups",
			type:"POST",
			data: {'provider_id':providerId,'table_name':tableName},
			beforeSend: function() {
				
			},
			headers: {
		    	'X-CSRF-Token': '{{ csrf_token() }}'
    		},
			success: function(popupColumnDetails) {
				var popupColumnDetails = JSON.parse(popupColumnDetails);
				if(popupColumnDetails.hasOwnProperty("type"))
				{
					$("#popupColumnTable tbody tr").remove();
					$('<tr>').append(
            		$('<td class="noColumnDisplay" colspan="4">').text(popupColumnDetails.message)).appendTo('#popupTableFields');
				}
				if (typeof popupColumnDetails !== 'undefined' && popupColumnDetails.length > 0) {
					$("#popupColumnTable tbody tr").remove();
					$.each(popupColumnDetails, function(columnindex, columnItem) {
						var popupFieldValues = '<select class="popupFieldDate" id="popupFieldDate"  name='+columnItem.fieldName+'>';
						$.each(columnItem.popupTableFields, function(fieldIndex, fieldItem) {
							popupFieldValues += '<option value="' + fieldItem +'">'+ fieldItem + '</option>';
						});
						popupFieldValues += '</select>';
				        var $tr = $('<tr>').append(
				            $('<td>').text(columnItem.fieldName),
				            $('<td>').text(columnItem.popupTable),
				            $('<td>').text(columnItem.popupTableId),
				            $('<td>').html(popupFieldValues)
				        ).appendTo('#popupTableFields');
				    });
				} 
			}
		});
	});
	$("#databaseProvider").change(function() {
		var providerTableTag = document.getElementById("providerTableList");
		jQuery("#providerTableList").find('option').remove();
		var providerId = $(this).val();
		$("#existTableProvider").val(providerId);
		$("#newTableProvider").val(providerId);
		$.ajax({
			url: "{{ url('get_schema_tables') }}/" + providerId,
			type:"get",
			beforeSend: function() {
				
			},
			headers: {
		    	'X-CSRF-Token': '{{ csrf_token() }}'
    		},
			success: function(providerTableList) {
				var tableList = JSON.parse(providerTableList);
				if(tableList.hasOwnProperty("type"))
				{
					var tableOptionElement = document.createElement("option");
					tableOptionElement.textContent = tableList.message;
				    tableOptionElement.value = tableList.type;
				    providerTableTag.appendChild(tableOptionElement);
				} else {
					for (var tableIndex = 0; tableIndex < tableList.length; tableIndex++) {
		                var tableName = tableList[tableIndex];
		                var tableOptionElement = document.createElement("option");
		                tableOptionElement.textContent = tableName;
					    tableOptionElement.value = tableName;
					    providerTableTag.appendChild(tableOptionElement);
		            }
		        }
		        $("#providerTableList").trigger('change');
			}
		});
	});
	$("#databaseProvider").trigger('change');
	$('.delete_module').on("click", function () {
    	var module_id = $(this).attr('module_id');
		var module_name = $(this).attr('module_name');
		$("#moduleNameStr").html(module_name);
		$url = $("#module_del_form").attr("action");
		console.log($url);

		$("#module_del_form").attr("action", $url.replace("/0", "/delete/"+module_id));
		$("#module_delete_confirm").modal('show');
		$.ajax({
			url: "{{ url('get_module_files') }}/" + module_id,
			type:"get",
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
	
	$('.module_Action_submit').on("click", function (eventObject) {
		// Get the Login Name value and trim it
	    var selecteFields = $.trim($('#selectedModules').val());

	    // Check if empty of not
	    if (selecteFields  === '') {
	        displayTosterMessage('error','Please select at least one module.');
	        return false;
	    }

		var module_names = $("#selectedModulesName").val();
		$("#module_mass_delete_confirm").modal('show');
		var moduleNameArray = module_names.split(',');
		var moduleList = "<ul>";
		for (moduleIndex = 0; moduleIndex < moduleNameArray.length; moduleIndex++) { 
			moduleList += "<li>" + moduleNameArray[moduleIndex] + "</li>";
		}
		moduleList += "</ul>";
		$("#moduleDeleteList").html(moduleList);

	});
	$('input[name=icon]').iconpicker();
	
	$("#dt_modules").DataTable({
		"aaSorting": [],
		"columnDefs": [ {
			"targets": [0],
			"orderable": false
		}]
	});

	$('input[name=name]').keypress(function(event) {
	  
	  var keyChar = String.fromCharCode(event.charCode);
      var re = /^[a-zA-Z]+$/
      if(re.test(keyChar)){
      	$("#message").hide();
      	return true;
      }else{
      	$("#message").show();
      	return false;
      }

	});
});
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
