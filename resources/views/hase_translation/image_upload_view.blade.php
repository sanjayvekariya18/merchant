@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Image Upload
    @parent
@stop
{{-- page level styles --}}
@section('header_styles')
<script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
<script src="assets/kendoui/js/kendo.all.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.uniform.min.css">
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
@stop
@section('content')
<section class="content-header">
    <h1>Image Upload </h1>
    <ol class="breadcrumb">
        <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
        <li><a href="#"> Image Upload </a></li>
        <li class="active">Image</li>
    </ol>
</section>
<section class="content">
    <form method = 'POST' enctype="multipart/form-data" class="form-horizontal bv-form" action = '{!!url("image_upload")!!}' id="hase_image_upload_form">
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <div class="row">   
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save &amp; close</button>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff"
                               data-loop="true"></i> Image Uploads
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>
                    <div class="panel-body">

        <div class="form-group">
            <label for="live_Url" class="col-sm-3 control-label">Upload Live Url:</label>
            <div class="col-sm-8"> 
                <input type="text" name="imageData" id="imageData" class="form-control"/>
            </div>
        </div>

        <div class="form-group">
            <label for="local_url" class="col-sm-3 control-label">Upload Local Url:</label>
            <div class="col-sm-8">
   
             <input id="translation_image" name="translation_image[]" type="file" multiple class="form-control"/>
      
             </div>
        </div>
        <div class="form-group">
            <label for="albumName" class="col-sm-3 control-label">Album Name:</label>
            <div class="col-sm-8">
      
            <div><input onclick='imageUploadValidation()' onselect='imageUploadValidation()' name="albumName" id="albumName" class="form-control"/>
            </div>
            </div>
        </div>
        <div class="form-group">
            <label for="userName" class="col-sm-3 control-label">User Name:</label>
            <div class="col-sm-8">
                <input name="userName" id="userName" value="{{$userName}}" class="form-control"/>
            </div>
        </div>
        <div class="form-group">
            <label for="imageLocation" class="col-sm-3 control-label">Location:</label>
            <div class="col-sm-8">
                <input name="imageLocation" id="imageLocation" class="form-control"/>
            </div>
        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </section>
</section>
<script type="text/javascript">
    function imageUploadValidation(){
        var imageData =$('#imageData').val();
        var translation_image=$('#translation_image').val();
        if( imageData == '' && translation_image == ''){
            alert('Please Enter Local Or Live Url');
       }
    }
</script>
<script type="text/javascript">
var albumName={!!$albumNameList!!}
jQuery("#albumName").kendoAutoComplete({
  autoWidth: true,
   dataSource:albumName,
   filter: "startswith",
   placeholder: "Select Album Name",
});
</script>
@endsection

{{-- page level scripts --}}
@section('footer_scripts')
<script type="text/javascript" src="{{asset('assets/vendors/moment/js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseImageUpload.js')}}"></script>
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