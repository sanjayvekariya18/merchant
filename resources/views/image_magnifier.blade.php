@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
        Image Magnifier
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-magnify/css/bootstrap-magnify.min.css')}}" />
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Image Magnifier
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-home"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="#">
                        Gallery
                    </a>
                </li>
                <li class="active">
                    Image Magnifier
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary" style="padding-bottom:70px;">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="livicon" data-name="zoom-in" data-c="#fff" data-hc="#fff" data-size="18"
                                   data-loop="true"></i>
                                Image Magnifier
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-3 col-xs-6">
                                    <a class="mag img-responsive">
                                        <br/>
                                        <img data-toggle="magnify" class="mag-style img-responsive"
                                             src="{{asset('assets/img/gallery/thumbs/24.jpg')}}" alt="image">
                                    </a>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <a class="mag img-responsive">
                                        <br/>
                                        <img data-toggle="magnify" src="{{asset('assets/img/gallery/thumbs/30.jpg')}}" alt="image"
                                             class="mag-style img-responsive">
                                    </a>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <a class="mag img-responsive">
                                        <br/>
                                        <img data-toggle="magnify" src="{{asset('assets/img/gallery/thumbs/20.jpg')}}" alt="image"
                                             class="mag-style img-responsive">
                                    </a>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <a class="mag img-responsive">
                                        <br/>
                                        <img data-toggle="magnify" src="{{asset('assets/img/gallery/thumbs/29.jpg')}}" alt="image"
                                             class="mag-style img-responsive">
                                    </a>
                                </div>
                            </div>
                            <!--row-->
                            <div class="row" style="margin-top:40px;">
                                <div class="col-md-3 col-xs-6">
                                    <a class="mag img-responsive">
                                        <br/>
                                        <img data-toggle="magnify" src="{{asset('assets/img/gallery/thumbs/29.jpg')}}" alt="image"
                                             class="mag-style img-responsive">
                                    </a>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <a class="mag img-responsive">
                                        <br/>
                                        <img data-toggle="magnify" src="{{asset('assets/img/gallery/thumbs/8.jpg')}}" alt="image"
                                             class="mag-style img-responsive">
                                    </a>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <a class="mag img-responsive">
                                        <br/>
                                        <img data-toggle="magnify" src="{{asset('assets/img/gallery/thumbs/31.jpg')}}" alt="image"
                                             class="mag-style img-responsive">
                                    </a>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <a class="mag img-responsive">
                                        <br/>
                                        <img data-toggle="magnify" src="{{asset('assets/img/gallery/thumbs/20.jpg')}}" alt="image"
                                             class="mag-style img-responsive">
                                    </a>
                                </div>
                            </div>
                            <div class="row" style="margin-top:40px;">
                                <div class="col-md-3 col-xs-6">
                                    <a class="mag img-responsive">
                                        <br/>
                                        <img data-toggle="magnify" src="{{asset('assets/img/gallery/thumbs/30.jpg')}}" alt="image"
                                             class="mag-style img-responsive">
                                    </a>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <a class="mag img-responsive">
                                        <br/>
                                        <img data-toggle="magnify" src="{{asset('assets/img/gallery/thumbs/32.jpg')}}" alt="image"
                                             class="mag-style img-responsive">
                                    </a>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <a class="mag img-responsive">
                                        <br/>
                                        <img data-toggle="magnify" src="{{asset('assets/img/gallery/thumbs/17.jpg')}}" alt="image"
                                             class="mag-style img-responsive">
                                    </a>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <a class="mag img-responsive">
                                        <br/>
                                        <img data-toggle="magnify" src="{{asset('assets/img/gallery/thumbs/8.jpg')}}" alt="image"
                                             class="mag-style img-responsive">
                                    </a>
                                </div>
                            </div>
                            <div class="row" style="margin-top:40px;">
                                <div class="col-md-3 col-xs-6">
                                    <a class="mag img-responsive">
                                        <br/>
                                        <img data-toggle="magnify" src="{{asset('assets/img/gallery/thumbs/31.jpg')}}" alt="image"
                                             class="mag-style img-responsive">
                                    </a>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <a class="mag img-responsive">
                                        <br/>
                                        <img data-toggle="magnify" src="{{asset('assets/img/gallery/thumbs/8.jpg')}}" alt="image"
                                             class="mag-style img-responsive">
                                    </a>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <a class="mag img-responsive">
                                        <br/>
                                        <img data-toggle="magnify" src="{{asset('assets/img/gallery/thumbs/30.jpg')}}" alt="image"
                                             class="mag-style img-responsive">
                                    </a>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <a class="mag img-responsive">
                                        <br/>
                                        <img data-toggle="magnify" src="{{asset('assets/img/gallery/thumbs/29.jpg')}}" alt="image"
                                             class="mag-style img-responsive">
                                    </a>
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
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-magnify/js/bootstrap-magnify.js')}}" ></script>
    <!-- end of page level js -->
@stop
