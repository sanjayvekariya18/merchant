@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Staff
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
    <h1>Staff</h1>
      <ol class="breadcrumb">
          <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
          <li><a href="#"> Staff</a></li>
      </ol>
</section>
<section class="content">
    <?php if(in_array("add", $permissions)): ?>
        <form class = 'col s3' method = 'get' action = '{!!url("hase_staff")!!}/create'>
            <button class = 'btn btn-primary' type = 'submit'>Create New Staff</button>
        </form>
    <?php endif; ?>
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> Staff
                    </h4>
                </div>
                <input type="hidden" id="request_url" value="{{url('hase_staff')}}">
                {{ csrf_field() }}
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div id="staffGrid"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
@endsection
<script type="text/javascript">
var userId="{{$user_id}}";
localStorage.setItem("userId",userId);
localStorage.setItem("accessibility","{{$accessibility}}");
</script>
<script src="{{asset('assets/kendoui/js/jquery.min.js')}}" type="text/javascript"></script>
@section('footer_scripts')
<script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseStaffIndex.js')}}"></script>
@stop