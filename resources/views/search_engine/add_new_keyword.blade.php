@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Add keywords
    @parent
@stop
{{-- page level styles --}}
@section('header_styles')
@stop
@section('content')
<section class="content-header">
    <h1>Add Keyword </h1>
    <ol class="breadcrumb">
        <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
        <li class="active">Add Keyword</li>
    </ol>
</section>
<section class="content">
    <form method = 'POST' enctype="multipart/form-data" class="form-horizontal bv-form" action = '{!!url("add_keyword")!!}' id="add_keyword_form">
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <br/>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff"
                               data-loop="true"></i> Add Keywords
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                           <label for="keyword" class="col-sm-1 control-label"><b>Keyword</b></label>
                                <div class="col-sm-5">                               
                                    <input id="keyword" name="keyword" type="text" class="form-control required">
                                </div>
                        </div>
                        <div class="form-group">
                                <div class="col-sm-5">                               
                                    <input type="hidden" value="" id="activityIdentity" name="activityIdentity" >
                                </div>
                        </div>
                        <div class="form-group">
                                <div class="col-sm-5">                               
                                    <input type="hidden" value="" id="regionIdentity" name="regionIdentity" >
                                </div>
                        </div>
                        <button type="button" class="btn btn-success" id="saveStyle" onclick="setRegionActivityValue('add_keyword','')" ><span class="glyphicon glyphicon-ok-sign"></span> Save</button>&nbsp &nbsp &nbsp<button type="button" class="btn btn-success" id="saveStyle" onclick='javascript:goBack()' ><span class="glyphicon glyphicon-ok-sign"></span> Cancle</button><br><br>
                    <iframe name="activityIframe" id="activityIframe" src="activity-region-tree" scrolling="auto" frameborder="0"  width="825" height="600"> </iframe>

                    </div>
                </div>
            </div>
        </div>
    </form>
    </section>
</section>
@endsection
{{-- page level scripts --}}
@section('footer_scripts')
<script type="text/javascript" src="{{asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/AddKeywords.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/RegionActivityValue.js')}}"></script>
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








