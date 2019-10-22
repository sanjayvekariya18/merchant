@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Translation queue
    @parent
@stop
{{-- page level styles --}}
@section('header_styles')

@stop
@section('content')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
<script src="assets/kendoui/js/kendo.all.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.uniform.min.css">
<body onkeydown="if_tab(event.which)">
    <span id="countdown-1" style="font-family: Verdana; font-size: 28px; font-weight: bold">60 </span>
    <br />
    <span> <b> seconds </b>
    </span>
</body>
<section class="content-header">
    <h1>Translation Queue List</h1>
    <ol class="breadcrumb">
        <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
        <li><a href="#"> Translation queue List</a></li>
        <li class="active">Queue</li>
    </ol>
</section>
<section class="content">
    <form method = 'POST' enctype="multipart/form-data" class="form-horizontal bv-form" action = '{!!url("update-image-word-text")!!}' id="hase_image_upload_form">
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <div class="row">   
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save</button>
                
            </div>
        </div>
        <br/>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff"
                               data-loop="true"></i> Translation Queue
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>
                    <div class="panel-body">
               <!--  <?php //if($fieldShow != 'image_url') {?>    
                @if($languageValue == 1)
                    <select name="userKnownLanguage" id="userKnownLanguage">
                        <option value="Select Language">Select Language</option> @foreach ($userLanguageListData as $languageValue)
                        <option value="{{$languageValue['language_code']}}">{{$languageValue['language_name']}}</option> @endforeach
                        </select></br> <br>
                @else
                    <ul><li><select name="userKnownLanguage" id="userKnownLanguage">
                        <option value="en_us">English</option> 
                        </select>&nbsp&nbsp&nbsp <a href="../users_language">User Can select known Language </a></li></ul>
                    </br> <br>

                @endif 
            <?php //} ?>-->
            <?php
            if($fieldShow == 'result_text') {

                ?>    
                <?php foreach ($imageWordTranslationQueue as $imageWordTranslationQueueValue){?> 
            <div style="float: left; padding-right: 100px; width: 20%"> 
                    <div style="width: 150%; height: 150px;  overflow-y:scroll; overflow-x: hidden;"> <?php echo $imageWordTranslationQueueValue->result_text; ?>  
            </div><br/><br/>
            <div style="width: 150%; height: 50px;"> <?php echo $imageWordTranslationQueueValue->tupleregex;?>  
            </div><br/><br/>
            <br/><br/>
            <textarea name=<?php echo $imageWordTranslationQueueValue->result_id;?> tabindex=<?php echo $tabIndexValue+1?> rows="3" cols="20"></textarea></br> <br />

            </div>
            <input type = 'hidden' name = 'translationQueueValue' value ={{$translationDynamicId}}>
            <?php }?>
            <?php } else {?>
            <table>
                <?php foreach ($imageWordTranslationQueue as $imageWordTranslationQueueValue){
                    $supportedImage = array('gif','jpg','jpeg','png'
                    );
                    $translationFieldName = $imageWordTranslationQueueValue->{$fieldShow};
                    $translationDetails = strtolower(pathinfo($translationFieldName, PATHINFO_EXTENSION)); 
                if (in_array($translationDetails, $supportedImage)) {?>

                    <tr>
                        <td style="width: 15%"> <div style="background: url(<?php echo $imageWordTranslationQueueValue->{$fieldShow}; ?>) no-repeat 50% 50%; background-size: 100%; width: 80%; height: 120px;"></div>
                        </td>
                <?php } else {?>
                        <td style="width: 15%"> <?php echo $imageWordTranslationQueueValue->{$fieldShow}; ?>  
                        </td>
                <?php }  ?>
                        <td style="width: 30%">
                            <textarea name=<?php echo $imageWordTranslationQueueValue->{$keyPrimary};?> tabindex=<?php echo $tabIndexValue+1?> rows="5" cols="30"></textarea><br><br>
                            <input type = 'hidden' name = 'translationQueueValue' value ={{$translationDynamicId}}>
                        </td>
            
            </tr>

            <?php } }?>
            
               
            </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </section>
</section>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseImageWordTranslationTimer.js')}}"></script>
<script type="text/javascript">
var randomValueDynamic="{{$randomValueDynamic}}";
localStorage.setItem("randomValueDynamic",randomValueDynamic);
var baseUrl="{{$baseUrl}}";
localStorage.setItem("baseUrl",baseUrl);
wordImageTranslateTimer();
</script>
@endsection

{{-- page level scripts --}}
@section('footer_scripts')
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