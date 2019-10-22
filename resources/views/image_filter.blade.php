@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
        Image Filter
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link type="text/css" rel="stylesheet"  href="{{asset('assets/vendors/cssgram/css/cssgram.min.css')}}" />
    <link type="text/css" rel="stylesheet"  href="{{asset('assets/vendors/dropify/css/dropify.css')}}">
    <link type="text/css" rel="stylesheet"  href="{{asset('assets/css/custom_css/image_filters.css')}}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Image Filters
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-home"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="#"> Gallery </a>
                </li>
                <li class="active">
                    Image Filters
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-picture-o"></i> Image Filters (Aden - Hudson)
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-xs-12 text-center">
                                        <figure id="imgfigure1">
                                            <input type="file" class=" dropify" id="filter-1" data-show-remove="false"
                                                   data-allowed-file-extensions="jpg png tif gif"
                                                   data-default-file="{{asset('assets/img/gallery/full/16.jpg')}}"/>
                                        </figure>
                                    </div>
                                    <div class="col-md-12 col-xs-12 text-center">
                                        <ul class="pagination">
                                            <li data-filter1="aden">
                                                <figure class="aden text-center">
                                                    <img class="temp_path1" src="{{asset('assets/img/gallery/full/16.jpg')}}" height="75"
                                                         alt="aden image">
                                                    <span>Aden</span>
                                                </figure>
                                            </li>
                                            <li data-filter1="brannan">
                                                <figure class="brannan text-center">
                                                    <img class="temp_path1" src="{{asset('assets/img/gallery/full/16.jpg')}}" height="75"
                                                         alt="brannan image">
                                                    <span>Brannan</span>
                                                </figure>
                                            </li>
                                            <li data-filter1="brooklyn">
                                                <figure class="brooklyn text-center">
                                                    <img class="temp_path1" src="{{asset('assets/img/gallery/full/16.jpg')}}" height="75"
                                                         alt="brooklyn image">
                                                    <span>Brooklyn</span>
                                                </figure>
                                            </li>
                                            <li data-filter1="clarendon">
                                                <figure class="clarendon text-center">
                                                    <img class="temp_path1" src="{{asset('assets/img/gallery/full/16.jpg')}}" height="75"
                                                         alt="clarendon image">
                                                    <span>Clarendon</span>
                                                </figure>
                                            </li>
                                            <li data-filter1="earlybird">
                                                <figure class="earlybird text-center">
                                                    <img class="temp_path1" src="{{asset('assets/img/gallery/full/16.jpg')}}" height="75"
                                                         alt="earlybird image">
                                                    <span class="font-ccc">Earlybird</span>
                                                </figure>
                                            </li>
                                            <li data-filter1="gingham">
                                                <figure class="gingham text-center">
                                                    <img class="temp_path1" src="{{asset('assets/img/gallery/full/16.jpg')}}" height="75"
                                                         alt="gingham image">
                                                    <span>Gingham</span>
                                                </figure>
                                            </li>
                                            <li data-filter1="hudson">
                                                <figure class="hudson text-center">
                                                    <img class="temp_path1" src="{{asset('assets/img/gallery/full/16.jpg')}}" height="75"
                                                         alt="hudson image">
                                                    <span>Hudson</span>
                                                </figure>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-picture-o"></i> Image Filters (Inkwell - Perpetua)
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-xs-12 text-center">
                                        <figure id="imgfigure2">
                                            <input type="file" class=" dropify" id="filter-2"
                                                   data-allowed-file-extensions="jpg png tif gif"
                                                   data-default-file="{{asset('assets/img/gallery/full/15.jpg')}}"
                                                   data-show-remove="false"/>
                                        </figure>
                                    </div>
                                    <div class="col-md-12 col-xs-12 text-center">
                                        <ul class="pagination">
                                            <li data-filter2="inkwell">
                                                <figure class="inkwell text-center">
                                                    <img class="temp_path2" src="{{asset('assets/img/gallery/full/15.jpg')}}" height="75"
                                                         alt="inkwell image">
                                                    <span>Inkwell</span>
                                                </figure>
                                            </li>
                                            <li data-filter2="lark">
                                                <figure class="lark text-center">
                                                    <img class="temp_path2" src="{{asset('assets/img/gallery/full/15.jpg')}}" height="75"
                                                         alt="lark image">
                                                    <span class="font-ccc">Lark</span>
                                                </figure>
                                            </li>
                                            <li data-filter2="lofi">
                                                <figure class="lofi text-center">
                                                    <img class="temp_path2" src="{{asset('assets/img/gallery/full/15.jpg')}}" height="75"
                                                         alt="lofi image">
                                                    <span>Lofi</span>
                                                </figure>
                                            </li>
                                            <li data-filter2="mayfair">
                                                <figure class="mayfair text-center">
                                                    <img class="temp_path2" src="{{asset('assets/img/gallery/full/15.jpg')}}" height="75"
                                                         alt="mayfair image">
                                                    <span>Mayfair</span>
                                                </figure>
                                            </li>
                                            <li data-filter2="moon">
                                                <figure class="moon text-center">
                                                    <img class="temp_path2" src="{{asset('assets/img/gallery/full/15.jpg')}}" height="75"
                                                         alt="moon image">
                                                    <span>Moon</span>
                                                </figure>
                                            </li>
                                            <li data-filter2="nashville">
                                                <figure class="nashville text-center">
                                                    <img class="temp_path2" src="{{asset('assets/img/gallery/full/15.jpg')}}" height="75"
                                                         alt="nashville image">
                                                    <span>Nashville</span>
                                                </figure>
                                            </li>
                                            <li data-filter2="perpetua">
                                                <figure class="perpetua text-center">
                                                    <img class="temp_path2" src="{{asset('assets/img/gallery/full/15.jpg')}}" height="75"
                                                         alt="perpetua image">
                                                    <span>Perpetua</span>
                                                </figure>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-picture-o"></i> Image Filters (Rise - Xpro2)
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-xs-12 text-center">
                                        <figure id="imgfigure3">
                                            <input type="file" class=" dropify" id="filter-3"
                                                   data-allowed-file-extensions="jpg png tif gif"
                                                   data-default-file="{{asset('assets/img/gallery/full/5.jpg')}}" data-show-remove="false"/>
                                        </figure>
                                    </div>
                                    <div class="col-md-12 col-xs-12 text-center">
                                        <ul class="pagination">
                                            <li data-filter3="rise">
                                                <figure class="rise text-center">
                                                    <img class="temp_path3" src="{{asset('assets/img/gallery/full/5.jpg')}}" height="75"
                                                         alt="rise image">
                                                    <span>Rise</span>
                                                </figure>
                                            </li>
                                            <li data-filter3="slumber">
                                                <figure class="slumber text-center">
                                                    <img class="temp_path3" src="{{asset('assets/img/gallery/full/5.jpg')}}" height="75"
                                                         alt="slumber image">
                                                    <span>Slumber</span>
                                                </figure>
                                            </li>
                                            <li data-filter3="toaster">
                                                <figure class="toaster text-center">
                                                    <img class="temp_path3" src="{{asset('assets/img/gallery/full/5.jpg')}}" height="75"
                                                         alt="toaster image">
                                                    <span class="font-ccc">Toaster</span>
                                                </figure>
                                            </li>
                                            <li data-filter3="valencia">
                                                <figure class="valencia text-center">
                                                    <img class="temp_path3" src="{{asset('assets/img/gallery/full/5.jpg')}}" height="75"
                                                         alt="valencia image">
                                                    <span>Valencia</span>
                                                </figure>
                                            </li>
                                            <li data-filter3="walden">
                                                <figure class="walden text-center">
                                                    <img class="temp_path3" src="{{asset('assets/img/gallery/full/5.jpg')}}" height="75"
                                                         alt="walden image">
                                                    <span>Walden</span>
                                                </figure>
                                            </li>
                                            <li data-filter3="willow">
                                                <figure class="willow text-center">
                                                    <img class="temp_path3" src="{{asset('assets/img/gallery/full/5.jpg')}}" height="75"
                                                         alt="willow image">
                                                    <span>Willow</span>
                                                </figure>
                                            </li>
                                            <li data-filter3="xpro2">
                                                <figure class="xpro2 text-center">
                                                    <img class="temp_path3" src="{{asset('assets/img/gallery/full/5.jpg')}}" height="75"
                                                         alt="xpro2 image">
                                                    <span>Xpro2</span>
                                                </figure>
                                            </li>
                                        </ul>
                                    </div>
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
<script type="text/javascript" src="{{asset('assets/js/custom_js/image_filters.js')}}"></script>
    <!-- end of page level js -->
@stop
