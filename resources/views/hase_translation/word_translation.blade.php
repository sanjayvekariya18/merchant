@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Translation
    @parent
@stop
{{-- page level styles --}}
@section('header_styles')
@stop
@section('content')
<section class="content-header">
    <h1>Translation View</h1>
    <ol class="breadcrumb">
        <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
        <li><a href="#"> Translation view</a></li>
        <li class="active">View</li>
    </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> Translation View
                    </h4>

                </div>
                <div class="panel-body">
                
<?php
define ('BASE_FILE_PATH',$baseUrl.'/tiki-language-translate/');

 ?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
<script src="assets/kendoui/js/kendo.all.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.uniform.min.css">
<script src="<?php echo BASE_FILE_PATH;?>js/LanguageTranslateConfiguration.js" charset="utf-8" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo BASE_FILE_PATH;?>styles/JqueryVirtualKeyboard.css">
<script src="<?php echo BASE_FILE_PATH;?>js/JqueryVirtualKeyboard.js" type="text/javascript"></script>
<script src="<?php echo BASE_FILE_PATH;?>js/LanguageTranslator.js" type="text/javascript"></script>

<div class="approveLanguageDropdown" style='float: right;'><input id="multiLanguageDropdown" /></div>
    
                </div>
            </div>
        </div>
    </section>
</section>
@endsection
{{-- page level scripts --}}
@section('footer_scripts')