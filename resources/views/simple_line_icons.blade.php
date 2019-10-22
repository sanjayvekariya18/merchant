@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Simple Line Icons
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/toastr/css/toastr.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/simple-line-icons/css/simple-line-icons.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/simple_line_icons.css')}}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <!--section starts-->
        <h1>Simple Line Icons</h1>
        <ol class="breadcrumb">
            <li>
                <a href="index">
                    <i class="fa fa-fw fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="#">
                    UI Features
                </a>
            </li>
            <li class="active">
                Simple Line Icons
            </li>
        </ol>
    </section>
    <!--section ends-->
    <section class="content">
        <!--main content-->
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-fw fa-font"></i> Simple Line Icons
                </h3>
                <span class="pull-right">
                            <i class="fa fa-fw fa-times removepanel clickable"></i>
                            <i class="fa fa-fw fa-chevron-up clickable"></i>
                        </span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <form role="form">
                            <div class="input-group">
                                <label for="icon-search" class="sr-only">Search</label>
                                <input type="text" name="icon-search" id="icon-search" class="form-control"
                                       placeholder="Search Icon">
                            </div>
                            <div class="input-group">
                                <label for="icon-size" class="sr-only">Size</label>
                                <input type="range" name="icon-size" id="icon-size" class="form-control" min="24"
                                       max="40" value="24">
                                <span class="input-group-addon icon-sizeshow">24px</span>
                            </div>
                        </form>
                    </div>
                </div>
                <h4 class="text-center">Click on any icon to copy its code to clipboard.</h4>
                <div class='row'>
                    <div class='col-xs-12'>
                        <!-- glyphicons-->
                        <div class="text-center">
                            <h3 class="text-left">
                                Simple Line Icons
                            </h3>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-user icons"></i><span class="name">user</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-people icons"></i><span class="name">people</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-user-female icons"></i><span class="name">user-female</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-user-follow icons"></i><span class="name">user-follow</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-user-following icons"></i><span
                                            class="name">user-following</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-user-unfollow icons"></i><span class="name">user-unfollow</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-login icons"></i><span class="name">login</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-logout icons"></i><span class="name">logout</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-emotsmile icons"></i><span class="name">emotsmile</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-phone icons"></i><span class="name">phone</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-call-end icons"></i><span class="name">call-end</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-call-in icons"></i><span class="name">call-in</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-call-out icons"></i><span class="name">call-out</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-map icons"></i><span class="name">map</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-location-pin icons"></i><span class="name">location-pin</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-direction icons"></i><span class="name">direction</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-directions icons"></i><span class="name">directions</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-compass icons"></i><span class="name">compass</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-layers icons"></i><span class="name">layers</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-menu icons"></i><span class="name">menu</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-list icons"></i><span class="name">list</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-options-vertical icons"></i><span
                                            class="name">options-vertical</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-options icons"></i><span class="name">options</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-arrow-down icons"></i><span class="name">arrow-down</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-arrow-left icons"></i><span class="name">arrow-left</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-arrow-right icons"></i><span class="name">arrow-right</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-arrow-up icons"></i><span class="name">arrow-up</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-arrow-up-circle icons"></i><span
                                            class="name">arrow-up-circle</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-arrow-left-circle icons"></i><span
                                            class="name">arrow-left-circle</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-arrow-right-circle icons"></i><span
                                            class="name">arrow-right-circle</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-arrow-down-circle icons"></i><span
                                            class="name">arrow-down-circle</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-check icons"></i><span class="name">check</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-clock icons"></i><span class="name">clock</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-plus icons"></i><span class="name">plus</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-minus icons"></i><span class="name">minus</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-close icons"></i><span class="name">close</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-event icons"></i><span class="name">event</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-exclamation icons"></i><span class="name">exclamation</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-organization icons"></i><span class="name">organization</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-trophy icons"></i><span class="name">trophy</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-screen-smartphone icons"></i><span
                                            class="name">screen-smartphone</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-screen-desktop icons"></i><span
                                            class="name">screen-desktop</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-plane icons"></i><span class="name">plane</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-notebook icons"></i><span class="name">notebook</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-mustache icons"></i><span class="name">mustache</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-mouse icons"></i><span class="name">mouse</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-magnet icons"></i><span class="name">magnet</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-energy icons"></i><span class="name">energy</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-disc icons"></i><span class="name">disc</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-cursor icons"></i><span class="name">cursor</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-cursor-move icons"></i><span class="name">cursor-move</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-crop icons"></i><span class="name">crop</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-chemistry icons"></i><span class="name">chemistry</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-speedometer icons"></i><span class="name">speedometer</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-shield icons"></i><span class="name">shield</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-screen-tablet icons"></i><span class="name">screen-tablet</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-magic-wand icons"></i><span class="name">magic-wand</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-hourglass icons"></i><span class="name">hourglass</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-graduation icons"></i><span class="name">graduation</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-ghost icons"></i><span class="name">ghost</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-game-controller icons"></i><span
                                            class="name">game-controller</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-fire icons"></i><span class="name">fire</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-eyeglass icons"></i><span class="name">eyeglass</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-envelope-open icons"></i><span class="name">envelope-open</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-envelope-letter icons"></i><span
                                            class="name">envelope-letter</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-bell icons"></i><span class="name">bell</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-badge icons"></i><span class="name">badge</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-anchor icons"></i><span class="name">anchor</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-wallet icons"></i><span class="name">wallet</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-vector icons"></i><span class="name">vector</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-speech icons"></i><span class="name">speech</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-puzzle icons"></i><span class="name">puzzle</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-printer icons"></i><span class="name">printer</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-present icons"></i><span class="name">present</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-playlist icons"></i><span class="name">playlist</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-pin icons"></i><span class="name">pin</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-picture icons"></i><span class="name">picture</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-handbag icons"></i><span class="name">handbag</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-globe-alt icons"></i><span class="name">globe-alt</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-globe icons"></i><span class="name">globe</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-folder-alt icons"></i><span class="name">folder-alt</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-folder icons"></i><span class="name">folder</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-film icons"></i><span class="name">film</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-feed icons"></i><span class="name">feed</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-drop icons"></i><span class="name">drop</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-drawer icons"></i><span class="name">drawer</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-docs icons"></i><span class="name">docs</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-doc icons"></i><span class="name">doc</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-diamond icons"></i><span class="name">diamond</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-cup icons"></i><span class="name">cup</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-calculator icons"></i><span class="name">calculator</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-bubbles icons"></i><span class="name">bubbles</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-briefcase icons"></i><span class="name">briefcase</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-book-open icons"></i><span class="name">book-open</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-basket-loaded icons"></i><span class="name">basket-loaded</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-basket icons"></i><span class="name">basket</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-bag icons"></i><span class="name">bag</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-action-undo icons"></i><span class="name">action-undo</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-action-redo icons"></i><span class="name">action-redo</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-wrench icons"></i><span class="name">wrench</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-umbrella icons"></i><span class="name">umbrella</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-trash icons"></i><span class="name">trash</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-tag icons"></i><span class="name">tag</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-support icons"></i><span class="name">support</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-frame icons"></i><span class="name">frame</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-size-fullscreen icons"></i><span
                                            class="name">size-fullscreen</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-size-actual icons"></i><span class="name">size-actual</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-shuffle icons"></i><span class="name">shuffle</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-share-alt icons"></i><span class="name">share-alt</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-share icons"></i><span class="name">share</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-rocket icons"></i><span class="name">rocket</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-question icons"></i><span class="name">question</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-pie-chart icons"></i><span class="name">pie-chart</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-pencil icons"></i><span class="name">pencil</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-note icons"></i><span class="name">note</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-loop icons"></i><span class="name">loop</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-home icons"></i><span class="name">home</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-grid icons"></i><span class="name">grid</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-graph icons"></i><span class="name">graph</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-microphone icons"></i><span class="name">microphone</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-music-tone-alt icons"></i><span
                                            class="name">music-tone-alt</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-music-tone icons"></i><span class="name">music-tone</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-earphones-alt icons"></i><span class="name">earphones-alt</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-earphones icons"></i><span class="name">earphones</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-equalizer icons"></i><span class="name">equalizer</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-like icons"></i><span class="name">like</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-dislike icons"></i><span class="name">dislike</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-control-start icons"></i><span class="name">control-start</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-control-rewind icons"></i><span
                                            class="name">control-rewind</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-control-play icons"></i><span class="name">control-play</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-control-pause icons"></i><span class="name">control-pause</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-control-forward icons"></i><span
                                            class="name">control-forward</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-control-end icons"></i><span class="name">control-end</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-volume-1 icons"></i><span class="name">volume-1</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-volume-2 icons"></i><span class="name">volume-2</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-volume-off icons"></i><span class="name">volume-off</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-calendar icons"></i><span class="name">calendar</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-bulb icons"></i><span class="name">bulb</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-chart icons"></i><span class="name">chart</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-ban icons"></i><span class="name">ban</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-bubble icons"></i><span class="name">bubble</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-camrecorder icons"></i><span class="name">camrecorder</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-camera icons"></i><span class="name">camera</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-cloud-download icons"></i><span
                                            class="name">cloud-download</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-cloud-upload icons"></i><span class="name">cloud-upload</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-envelope icons"></i><span class="name">envelope</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-eye icons"></i><span class="name">eye</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-flag icons"></i><span class="name">flag</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-heart icons"></i><span class="name">heart</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-info icons"></i><span class="name">info</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-key icons"></i><span class="name">key</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-link icons"></i><span class="name">link</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-lock icons"></i><span class="name">lock</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-lock-open icons"></i><span class="name">lock-open</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-magnifier icons"></i><span class="name">magnifier</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-magnifier-add icons"></i><span class="name">magnifier-add</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-magnifier-remove icons"></i><span
                                            class="name">magnifier-remove</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-paper-clip icons"></i><span class="name">paper-clip</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-paper-plane icons"></i><span class="name">paper-plane</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-power icons"></i><span class="name">power</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-refresh icons"></i><span class="name">refresh</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-reload icons"></i><span class="name">reload</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-settings icons"></i><span class="name">settings</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-star icons"></i><span class="name">star</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-symbol-female icons"></i><span class="name">symbol-female</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-symbol-male icons"></i><span class="name">symbol-male</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-target icons"></i><span class="name">target</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-credit-card icons"></i><span class="name">credit-card</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-paypal icons"></i><span class="name">paypal</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-social-tumblr icons"></i><span class="name">social-tumblr</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-social-twitter icons"></i><span
                                            class="name">social-twitter</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-social-facebook icons"></i><span
                                            class="name">social-facebook</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-social-instagram icons"></i><span
                                            class="name">social-instagram</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-social-linkedin icons"></i><span
                                            class="name">social-linkedin</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-social-pinterest icons"></i><span
                                            class="name">social-pinterest</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-social-github icons"></i><span class="name">social-github</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-social-google icons"></i><span class="name">social-google</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-social-reddit icons"></i><span class="name">social-reddit</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-social-skype icons"></i><span class="name">social-skype</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-social-dribbble icons"></i><span
                                            class="name">social-dribbble</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-social-behance icons"></i><span
                                            class="name">social-behance</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-social-foursqare icons"></i><span
                                            class="name">social-foursqare</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-social-soundcloud icons"></i><span
                                            class="name">social-soundcloud</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-social-spotify icons"></i><span
                                            class="name">social-spotify</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-social-stumbleupon icons"></i><span
                                            class="name">social-stumbleupon</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-social-youtube icons"></i><span
                                            class="name">social-youtube</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-social-dropbox icons"></i><span
                                            class="name">social-dropbox</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-social-vkontakte icons"></i><span
                                            class="name">social-vkontakte</span>
                                </div>
                            </div>
                            <div class="icon-preview-box col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="preview">
                                    <i class="icon-social-steam icons"></i><span class="name">social-steam</span>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                </div>
            </div>
        </div>
        <!--main content ends-->
        <!--row end-->
        @include('layouts.right_sidebar')
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
    <script type="text/javascript" src="{{asset('assets/vendors/toastr/js/toastr.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/simple_line_icons.js')}}"></script>
    <!-- end of page level js -->
@stop