@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Roles
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css --><link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.common.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.rtl.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.default.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.mobile.all.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.blueopal.min.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">

<style type="text/css">

	.panel-body label{
		font-weight: bold;
	}
	.panel-body div.form-group{
		background-color: #d9ecf5;
	    padding: 15px 10px;
	}
</style>
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Roles</h1>
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
    <div class="row">
        <div class="col-md-12">
            <?php if(in_array("add", $permissions)): ?>
                <a href='{!!url("hase_staff_group")!!}/create' class='btn btn-primary btn-inline'>Create New Roles
                </a>
            <?php endif; ?>
        </div>
    </div>
    <br>
    
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> Roles List
                    </h4>
                </div>
                <input type="hidden" id="request_url" value="{{url('hase_staff_group')}}">
				{{ csrf_field() }}
                <div class="panel-body">
                    <div class="table-responsive">
                        <div id="staffGrid"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- form-modal -->
        <div id="top_modal" class="modal fade animated" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #d9ecf5;">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">New Roles</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form method='POST' action='{!!url("hase_staff_group")!!}/cloneRole' id="roleForm" class="form-horizontal" role="form">
                                    {{ csrf_field() }}
                                    <div class="preloader" style="background: none !important; ">
                                        <div class="loader_img">
                                            <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
                                        </div>
                                    </div>                                    
                                    <div class="form-group">
                                        <label for="Name" class="col-sm-4">Name</label>
                                        <div class="col-sm-8">
                                            <input id="staff_group_name" name="staff_group_name" type="text" class="form-control" required="">
                                        </div>
                                    </div>                                  
                                    <div class="modal-footer" style="padding-left: 33%;text-align:inherit">
                                        <button type="Submit" id="cloneRole" value="1" class="btn btn-success">Submit</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                                            Close
                                        </button>
                                    </div>
                                    <input type="hidden" id="group_id" name="group_id" value="" >
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>    
@endsection
<script type="text/javascript">
localStorage.setItem("accessibility","{{$accessibility}}");
</script>
<script src="{{asset('assets/kendoui/js/jquery.min.js')}}" type="text/javascript"></script>
@section('footer_scripts')
<script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/RolesIndex.js')}}"></script>
@stop