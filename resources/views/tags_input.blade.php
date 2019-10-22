@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Tags Input
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/insignia/css/insignia.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/insignia_custom.css')}}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Tags Input</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-home"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="#">UI Features</a>
                </li>
                <li class="active">
                    Tags Input
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-bell-o"></i> Markup
                            </h3>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div class="example example_markup">
                                <div class='parent'>
                                    <label for='ty' class="m-t-b-15">Enter a space-separated list of tags:</label>
                                    <div class='input'>
                                        <input id='ty' value="hi">
                                    </div>
                                </div>
                                <label for='custom' class="m-t-b-15">Enter a list of tags using a custom
                                    delimiter: </label>
                                <div class='input'>
                                    <input id='custom' value="hello">
                                </div>
                                <label for='del' class="m-t-b-15">If you want, you can allow humans to remove tags by
                                    clicking on the
                                    crosses.</label>
                                <div class='input'>
                                    <input id='del' value="welcome">
                                </div>
                                <label for='def' class="m-t-b-15">Default values are naturally welcome.</label>
                                <div class='input'>
                                    <input id='def' value='tagging as a service'>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-tags"></i> Categorizing tags
                            </h3>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div class="example example_tagclass">
                                <label for='dup' class="m-t-b-15">Duplicates are okay sometimes: </label>
                                <div class='input'>
                                    <input id='dup' value='hello hello hello'>
                                </div>
                                <label for='lng' class="m-t-b-15">Really long lists behave reasonably well, too.</label>
                                <div class='input'>
                                    <input id='lng'
                                           value='Really long lists behave reasonably well, too. Especially if you take into account this is JavaScript!'>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--row end-->
            @include('layouts.right_sidebar')
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/insignia/js/insignia.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/insignia_custom.js')}}"></script>
<!-- end of page level js -->
@stop
