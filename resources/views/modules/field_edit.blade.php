@extends('layouts/default')

@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/css/AdminLTE.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/bootstrap-slider/slider.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
@stop

@section('content')
<section class="content">
	<section class="content-header">
	    <h1>Edit Field Details</h1>
	</section>
<div class="box">
	<div class="box-header">
	</div>
	<div class="box-body">
		<div class="table-select-content">
			<div class="preloader" style="background: none !important; ">
		        <div class="loader_img">
		            <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
		        </div>
		    </div>
		</div>
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				{!! Form::model($field, ['route' => ['module_fields.update', $field->id ], 'method'=>'PUT', 'id' => 'field-edit-form']) !!}
					{{ Form::hidden("module_id", $module->id) }}
					{{ Form::hidden("provider_id", $module->provider_id,array('id' => 'provider_id')) }}
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
					
					<div id="precision_value">
						<div class="form-group">
							<label for="precision_value">Precision:</label>
	                       	{{ Form::number("precision_value", null, ['class'=>'form-control', 'placeholder'=>'precision Value','min'=>1,'max'=>8]) }}
						</div>
					</div>

					<div id="unique_val">
						<div class="form-group">
							<label for="unique">Unique:</label>
							{{ Form::checkbox("unique", null, null,array("class"=>"switchActionEvents")) }}
							<div class="Switch Round Off" style="vertical-align:top;margin-left:10px;"><div class="Toggle"></div></div>
						</div>
					</div>

					<div class="form-group">
						<label for="defaultvalue">Default Value :</label>
						{{ Form::text("defaultvalue", null, ['class'=>'form-control', 'placeholder'=>'Default Value']) }}
					</div>
					
					<div id="length_div">
						<div class="form-group">
							<label for="minlength">Minimum :</label>
							{{ Form::number("minlength", null, ['class'=>'form-control', 'placeholder'=>'Default Value']) }}
						</div>
						
						<div class="form-group">
							<label for="maxlength">Maximum :</label>
							{{ Form::number("maxlength", null, ['class'=>'form-control', 'placeholder'=>'Default Value']) }}
						</div>
					</div>
					
					<div class="form-group">
						<label for="required">Required:</label>
						{{ Form::checkbox("required", null,null, array("class"=>"switchActionEvents")) }}
						<div class="Switch Round Off" style="vertical-align:top;margin-left:10px;"><div class="Toggle"></div></div>
					</div>

					<div class="form-group">
						<label for="visibility">Visibility:</label>
						{{ Form::checkbox("visibility", null, null, array("class"=>"switchActionEvents")) }}
						<div class="Switch Round Off" style="vertical-align:top;margin-left:10px;"><div class="Toggle"></div></div>
					</div>
					<div class="form-group">
						<label for="viewColumn">view Column:</label>
						@if($field->colname != $module->view_col)
							{{ Form::checkbox("view_col", null, null, array("class"=>"switchActionEvents")) }}
						@else
							{{ Form::checkbox("view_col", null, true, array("class"=>"switchActionEvents")) }}
						@endif
						<div class="Switch Round Off" style="vertical-align:top;margin-left:10px;"><div class="Toggle"></div></div>
					</div>
					
					<div class="form-group values">
						<label for="popup_vals" style="padding-right: 25px;">Values :</label>
						<?php
						$default_val = "";
						$popup_field_id = "";
						$popup_field_name = "";
						$popup_value_type_table = false;
						$popup_value_type_list = false;
						if(starts_with($field->popup_vals, "@")) {
							$popup_value_type_table = true;
							$default_val = str_replace("@", "", $field->popup_vals);
							$popup_field_id = $field->popup_field_id;
							$popup_field_name = $field->popup_field_name;

						} else if(starts_with($field->popup_vals, "[")) {
							$popup_value_type_list = true;
							$default_val = json_decode($field->popup_vals);
							$popup_field_id = "";
							$popup_field_name = "";
						} else {
							$popup_value_type_table = true;
						}
						?>
						<div class="radio" style="margin-bottom:20px;">
							<label style="padding-right: 25px;">{{ Form::radio("popup_value_type", "table", $popup_value_type_table) }} From Table</label>
							<label>{{ Form::radio("popup_value_type", "list", $popup_value_type_list) }} From List</label>
						</div>
						<div class="form-group popup_vals_table_data">
							{{ Form::select("popup_vals_table", $tables, $default_val, ['id'=>'popup_vals_table','class'=>'form-control', 'rel' => '']) }}

							<label for="popup_field_id" style="margin-top: 10px;">Option Value Field</label>
							<select id="popup_field_id" name="popup_field_id" class="form-control" data-value="{{$popup_field_id}}">
								<option value="1">id</option>
								<option value="2">name</option>
							</select>
							
							<label for="popup_field_name" style="margin-top: 10px;">Option Text Field</label>
							<select id="popup_field_name" name="popup_field_name" class="form-control" data-value="{{$popup_field_name}}">
								<option value="1">id</option>
								<option value="2">name</option>
							</select>
						</div>
						
						<select class="form-control popup_vals_list" rel="taginput" multiple="1" data-placeholder="Add Multiple values (Press Enter to add)" name="popup_vals_list[]">
							@if(is_array($default_val))
								@foreach ($default_val as $value)
									<option selected>{{ $value }}</option>
								@endforeach
							@endif
						</select>
						
						<?php
						// print_r($tables);
						?>
					</div>
					
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url('modules/'.$module->id) }}">Cancel</a></button>
					</div>
				{!! Form::close() !!}
				
				@if($errors->any())
				<ul class="alert alert-danger">
					@foreach($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
				@endif
			</div>
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
</section>
@endsection

@section('footer_scripts')
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
<script src="{{ asset('la-assets/plugins/jQueryUI/jquery-ui.js') }}"></script>
<script src="{{ asset('la-assets/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('la-assets/plugins/bootstrap-datetimepicker/moment.min.js') }}"></script>
<script src="{{ asset('la-assets/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js') }}"></script>
<script src="{{ asset('la-assets/plugins/stickytabs/jquery.stickytabs.js') }}"></script>
<script src="{{ asset('la-assets/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('la-assets/plugins/dropzone/dropzone.js') }}"></script>
<script src="{{ asset('la-assets/js/app.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/BootstrapSwitch/BootstrapSwitch.js')}}"></script>
<script>
$(function () {
	$("select.popup_vals_list").hide();	
	$("select.popup_vals_list").next().hide();
	$(".popup_vals_table_data").hide();
	
	function showValuesSection() {
		var ft_val = $("select[name='field_type']").val();
		if(ft_val == 7 || ft_val == 15 || ft_val == 18 || ft_val == 20 || ft_val == 25) {
			$(".form-group.values").show();
		} else {
			$(".form-group.values").hide();
		}
		
		$('#length_div').removeClass("hide");
		if(ft_val == 2 || ft_val == 4 || ft_val == 5 || ft_val == 7 || ft_val == 9 || ft_val == 11 || ft_val == 12 || ft_val == 15 || ft_val == 18 || ft_val == 21 || ft_val == 24 || ft_val == 25) {
			$('#length_div').addClass("hide");
		}
		
		$('#unique_val').removeClass("hide");
		if(ft_val == 1 || ft_val == 2 || ft_val == 3 || ft_val == 7 || ft_val == 9 || ft_val == 11 || ft_val == 12 || ft_val == 15 || ft_val == 18 || ft_val == 20 || ft_val == 21 || ft_val == 24 || ft_val == 25) {
			$('#unique_val').addClass("hide");
		}

		$('#precision_value').addClass("hide");
		if(ft_val == 3)
		{
			$('#precision_value').removeClass("hide");
			$('#precision_value').addClass("show");
		}
	}

	$("#popup_vals_table").on("change",function(){
		$('.table-select-content .preloader').show();
        $('.table-select-content .preloader img').show();
		var schema_name = $(this).val();
		var providerId  = $("#provider_id").val();
		console.log(providerId);
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



	$("select[name='field_type']").on("change", function() {
		showValuesSection();
	});

	showValuesSection();

	function showValuesTypes() {
		
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
	
	$("input[name='popup_value_type']").on("change", function() {
		showValuesTypes();
	});
	showValuesTypes();

	$.validator.addMethod("data-rule-banned-words", function(value) {
		return $.inArray(value, ['files']) == -1;
	}, "Column name not allowed.");

	$("#field-edit-form").validate({
		
	});

	$("#popup_vals_table").trigger('change');
	
	setTimeout(function(){ 
		$("#popup_field_id").val($("#popup_field_id").attr("data-value"));
		$("#popup_field_name").val($("#popup_field_name").attr("data-value"));
	}, 3000);
	
});
</script>
@stop