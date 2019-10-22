@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
        Dropify
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/blueimp-gallery-with-desc/css/blueimp-gallery.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/dropify/css/dropify.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/dropify.css')}}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Dropify
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-home"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="#"> Gallery</a>
                </li>
                <li class="active">
                    Dropify
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-dropbox"></i> Dropify
                            </h3>
                        </div>
                        <div class="panel-body p-30">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Dropify Basic</h5>
                                    <input type="file" class="dropify"/>
                                </div>
                                <div class="col-md-6">
                                    <h5>AllowedFileExtensions (PDF, PNG and PSD )</h5>
                                    <input type="file" class="dropify" data-allowed-file-extensions="pdf png psd"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Max File Size</h5>
                                    <input type="file" data-max-file-size="3M"/>
                                </div>
                                <div class="col-md-6">
                                    <h5>Disabled</h5>
                                    <input type="file" class="dropify" disabled="disabled"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Default File</h5>
                                    <input type="file" class="dropify" data-default-file="{{asset('assets/img/2.jpg')}}"/>
                                </div>
                                <div class="col-md-6">
                                    <h5>Without Remove Button</h5>
                                    <input type="file" class="dropify" data-show-remove="false"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row -->
        @include('layouts.right_sidebar')
        <!-- right side bar end -->
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/dropify/js/dropify.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/dropify_custom.js')}}" ></script>
    <!-- end of page level js -->
@stop

