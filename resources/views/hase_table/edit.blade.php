@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Edit Table
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
    <link href="{{asset('assets/vendors/toastr/css/toastr.min.css')}}" rel="stylesheet"/>
    <!--end of page level css-->
@stop
@section('content')

<section class="content-header">
    <h1>Edit Table</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> Restaurant</a>
        </li>
        <li class="active">
            Edit Table
        </li>
    </ol>
</section>
<section class="content">
    <form id="restaurant_tables_form" method='POST' action='{!! url("hase_table")!!}/{!!$hase_table->seating_id!!}/update' class="form-horizontal">
        <input type='hidden' name='_token' value='{{Session::token()}}'>
        <input id="table_id" name="table_id" type="hidden" value="{!!$hase_table->seating_id!!}">
        <input id="location_id" name="location_id" type="hidden" value="{!!$hase_table->location_id!!}">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitbutton" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitbutton" value="SaveClose" class= 'btn btn-primary btn-inline'>Save &amp; Close</button>
                <a href="{!!url("hase_table")!!}" class='btn btn-primary btn-inline'>
                    <i class="fa fa-angle-double-left"></i>
                </a>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff"
                               data-loop="true"></i> Edit Table
                        </h3>
                        <span class="pull-right">
                            <i class="fa fa-fw fa-chevron-up clickable"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="seating_name" class="col-sm-3 control-label">Name</label>
                            <div class="col-sm-9">
                                <input id="seating_name" name="seating_name" type="text" class="form-control required" value="{!!$hase_table->seating_name!!}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="min_capacity" class="col-sm-3 control-label">Minimum Capacity</label>
                            <div class="col-sm-9">
                                <input id="min_capacity" name="min_capacity" type="text" class="form-control required" value="{!!$hase_table->min_capacity!!}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="max_capacity" class="col-sm-3 control-label">Maximum Capacity</label>
                            <div class="col-sm-9">
                                <input id="max_capacity" name="max_capacity" type="text" class="form-control required" value="{!!$hase_table->max_capacity!!}">
                            </div>
                        </div>
                        
                        <input id="autobook" name="autobook" type="hidden" value="{!!$hase_table->autobook!!}">
                        
                        <div class="form-group">
                            <label for="status" class="col-sm-3 control-label">Status</label>
                            <div class="make-switch col-sm-9" data-on="danger" data-off="default">
                                <input type="checkbox" name="status" id="status" data-on-text="Enabled" data-off-text="Disabled" value="{!!$hase_table->status!!}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection

{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/toastr/js/toastr.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseTableCreate.js')}}"></script>
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
<!-- end of page level js -->
@stop