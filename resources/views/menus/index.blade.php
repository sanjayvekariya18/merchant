@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Dynamic Menus
    @parent
@stop
@section('header_styles')
    <link href="{{ asset('la-assets/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('la-assets/css/ionicons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('la-assets/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />
    <style type="text/css">
    	#menu-nestable button[data-action=collapse] {
    		display: block;
		}
	</style>
@stop
@section('content')
<?php
use App\Helpers\LAHelper;
?>
<section class="content-header">
    <h1>Dynamic Menus</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Dynamic Menus
        </li>
    </ol>
</section>
<section class="content">

<div class="box box-success menus">
	<!--<div class="box-header"></div>-->
	<div class="box-body">
		<div class="row">
			<div class="col-md-6 col-lg-6">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab-modules" data-toggle="tab">Modules</a></li>
						<li><a href="#tab-custom-link" data-toggle="tab">Custom Links</a></li>
						<li><a href="#tab-module-add" data-toggle="tab">Add Module</a></li>
					</ul>
					<div class="tab-content" style="overflow-y: scroll;height: 650px;">
						<div class="tab-pane active" id="tab-modules">
							<ul>
							@foreach ($modules as $module)
								<li>
									<i class="fa {{ $module->fa_icon }}"></i> {{ $module->name }}
									<a style="margin-left: 5px;" module_id="{{ $module->id }}" class="addModuleMenu pull-right"><i class="fa fa-plus"></i></a>
									<a style="margin-left: 5px;" class="editModuleBtn btn btn-xs btn-success pull-right" info="{{ json_encode($module) }}"><i class="fa fa-edit"></i></a>
									<!-- <a href="#" data-toggle="modal" style="margin-left: 5px;" class="btn btn-xs btn-danger pull-right" data-target="#delete" data-link = "{!!url('menus')!!}/deleteModule/{!!$module->id!!}"><i class="fa fa-trash"></i></a> -->
								</li>
							@endforeach
							</ul>
						</div>
						<div class="tab-pane" id="tab-custom-link">

							{!! Form::open(['action' => 'MenuController@store', 'id' => 'menu-custom-form']) !!}
								<input type="hidden" name="type" value="custom">
								<div class="form-group">
									<label for="url" style="font-weight:normal;">URL</label>
									<input class="form-control" placeholder="URL" name="url" type="text" value="http://" data-rule-minlength="1" required>
								</div>
								<div class="form-group">
									<label for="name" style="font-weight:normal;">Label</label>
									<input class="form-control" placeholder="Label" name="name" type="text" value=""  data-rule-minlength="1" required>
								</div>
								<div class="form-group">
									<label for="icon" style="font-weight:normal;">Icon</label>
									<div class="input-group">
										<input class="form-control" placeholder="FontAwesome Icon" name="icon" type="text" value="fa-cube"  data-rule-minlength="1" required>
										<span class="input-group-addon"></span>
									</div>
								</div>
								<input type="submit" class="btn btn-primary pull-right mr10" value="Add to menu">
							{!! Form::close() !!}
						</div>
						<div class="tab-pane" id="tab-module-add">

							{!! Form::open(['action' => 'MenuController@store', 'id' => 'menu-custom-form']) !!}
								<input type="hidden" name="type" value="newmodule">
								<div class="form-group">
									<label for="url" style="font-weight:normal;">URL</label>
									<input class="form-control" placeholder="URL" name="url" type="text" value="" data-rule-minlength="1" required>
								</div>
								<div class="form-group">
									<label for="name" style="font-weight:normal;">Label</label>
									<input class="form-control" placeholder="Label" name="name" type="text" value=""  data-rule-minlength="1" required>
								</div>
								<div class="form-group">
									<label for="icon" style="font-weight:normal;">Icon</label>
									<div class="input-group">
										<input class="form-control" placeholder="FontAwesome Icon" name="icon" type="text" value="fa-cube"  data-rule-minlength="1" required>
										<span class="input-group-addon"></span>
									</div>
								</div>
								<input type="submit" class="btn btn-primary pull-right mr10" value="Add to Modules">
							{!! Form::close() !!}
						</div>
					</div><!-- /.tab-content -->
				</div><!-- nav-tabs-custom -->
			</div>
			<div class="col-md-6 col-lg-6" style="overflow: scroll;height: 700px;">
				<button class="collapseAll btn btn-primary btn-sm">Collapse All</button>
				<button class="expandAll btn btn-primary btn-sm">Expand All</button>
				<div class="dd" id="menu-nestable">
					<ol class="dd-list">
						<?php $active_url = Request::url();?>
						@foreach ($menus as $menu)
							<?php echo LAHelper::print_menu_editor($menu, $active_url); ?>
						@endforeach
					</ol>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="EditModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Edit Menu Item</h4>
			</div>
			{!! Form::open(['action' => ['MenuController@update', 1], 'id' => 'menu-edit-form']) !!}
			<input name="_method" type="hidden" value="PUT">
			<div class="modal-body">
				<div class="box-body">
                    <input type="hidden" name="type" value="custom">
					<div class="form-group">
						<label for="url" style="font-weight:normal;">URL</label>
						<input class="form-control" placeholder="URL" name="url" type="text" value="http://" data-rule-minlength="1" required>
					</div>
					<div class="form-group">
						<label for="name" style="font-weight:normal;">Label</label>
						<input class="form-control" placeholder="Label" name="name" type="text" value=""  data-rule-minlength="1" required>
					</div>
					<div class="form-group">
						<label for="icon" style="font-weight:normal;">Icon</label>
						<div class="input-group">
							<input class="form-control" placeholder="FontAwesome Icon" name="icon" type="text" value="fa-cube"  data-rule-minlength="1" required>
							<span class="input-group-addon"></span>
						</div>
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

<div class="modal fade" id="EditModalModule" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Edit Module Item</h4>
			</div>
			{!! Form::open(['action' => ['MenuController@update', 1], 'id' => 'module-edit-form']) !!}
			<input name="_method" type="hidden" value="PUT">
			<div class="modal-body">
				<div class="box-body">
                    <input type="hidden" name="type" value="editmodule">
					<div class="form-group">
						<label for="url" style="font-weight:normal;">Controller URL</label>
						<input class="form-control" placeholder="URL" name="url" type="text" value="" data-rule-minlength="1" required>
					</div>
					<div class="form-group">
						<label for="name" style="font-weight:normal;">Label</label>
						<input class="form-control" placeholder="Label" name="name" type="text" value=""  data-rule-minlength="1" required>
					</div>
					<div class="form-group">
						<label for="icon" style="font-weight:normal;">Icon</label>
						<div class="input-group">
							<input class="form-control" placeholder="FontAwesome Icon" name="icon" type="text" value="fa-cube"  data-rule-minlength="1" required>
							<span class="input-group-addon"></span>
						</div>
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

<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="Heading" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title custom_align" id="Heading">Delete Module</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Module?
                </div>
            </div>
            <div class="modal-footer ">
                <a href="#" class="btn btn-danger">
                    <span class="glyphicon glyphicon-ok-sign"></span> Yes
                </a>
                <button type="button" class="btn btn-success" data-dismiss="modal">
                    <span class="glyphicon glyphicon-remove"></span> No
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>

</section>

@endsection

@section('footer_scripts')
<script src="{{ asset('assets/js/custom_js/jquery.nestable.js') }}"></script>
<script src="{{ asset('assets/js/custom_js/fontawesome-iconpicker.js') }}"></script>
<!-- AdminLTE App -->
<!-- <script src="{{ asset('la-assets/js/app.min.js') }}" type="text/javascript"></script> -->

<script src="{{ asset('la-assets/plugins/stickytabs/jquery.stickytabs.js') }}" type="text/javascript"></script>
<script src="{{ asset('la-assets/plugins/slimScroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>

<script>
$(function () {
	$('input[name=icon]').iconpicker();

	$('#menu-nestable').nestable({
        group: 1
    });

    $('.collapseAll').click(function(){
    	$('#menu-nestable button[data-action=collapse]').trigger('click');
    })

    $('.expandAll').click(function(){
    	$('#menu-nestable button[data-action=expand]').trigger('click');
    })

	$('#menu-nestable').on('change', function() {
		var jsonData = $('#menu-nestable').nestable('serialize');
		// console.log(jsonData);
		$.ajax({
			url: "{{ url('/menus/update_hierarchy') }}",
			method: 'POST',
			data: {
				jsonData: jsonData,
				"_token": '{{ csrf_token() }}'
			},
			success: function( data ) {
				window.location.reload();
			}
		});
	});


	$("#menu-nestable .editMenuBtn").on("click", function() {
		var info = JSON.parse($(this).attr("info"));
		var url = $("#menu-edit-form").attr("action");
		index = url.lastIndexOf("/");
		url2 = url.substring(0, index+1)+info.id;
		// console.log(url2);
		$("#menu-edit-form").attr("action", url2)
		$("#EditModal input[name=url]").val(info.url);
		$("#EditModal input[name=name]").val(info.name);
		$("#EditModal input[name=icon]").val(info.icon);
		$("#EditModal").modal("show");
	});

	$(".editModuleBtn").on("click", function() {
		var info = JSON.parse($(this).attr("info"));
		var url = $("#module-edit-form").attr("action");
		index = url.lastIndexOf("/");
		url2 = url.substring(0, index+1)+info.id;
		// console.log(url2);
		$("#module-edit-form").attr("action", url2)
		$("#EditModalModule input[name=url]").val(info.controller_url);
		$("#EditModalModule input[name=name]").val(info.name);
		$("#EditModalModule input[name=icon]").val(info.fa_icon);
		$("#EditModalModule").modal("show");
	});

	$("#tab-modules .addModuleMenu").on("click", function() {
		var module_id = $(this).attr("module_id");
		$.ajax({
			url: "{{ url('menus') }}",
			method: 'POST',
			data: {
				type: 'module',
				module_id: module_id,
				"_token": '{{ csrf_token() }}'
			},
			success: function( data ) {
				// console.log(data);
				window.location.reload();
			}
		});
	});

	$('#delete').on('show.bs.modal', function(e) {
		$(this).find('.btn-danger').attr('href', $(e.relatedTarget).attr('data-link'));
	});
});
</script>
@stop