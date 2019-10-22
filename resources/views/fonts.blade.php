@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
        Font Icons
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/simple-line-icons/css/simple-line-icons.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/fonts.css')}}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Font Icons</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="{{URL::to('index')}}">
                        <i class="fa fa-fw fa-home"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="#"> UI Features</a>
                </li>
                <li class="active">
                    Font Icons
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content p-l-r-15">
            <h4>You can use different sets of icon fonts:</h4>
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <h4 class="iconset_name">Font Awesome Icons</h4>
                    <div class="icon_group">
                        <div class="icon_set fontawesome_icons text-center">
                            <div class="row">
                                <div class="col-xs-2"><i class="fa fa-fw fa-bolt"></i></div>
                                <div class="col-xs-2"><i class="fa fa-fw fa-bullhorn"></i></div>
                                <div class="col-xs-2"><i class="fa fa-fw fa-clock-o"></i></div>
                                <div class="col-xs-2"><i class="fa fa-fw fa-cloud-upload"></i></div>
                                <div class="col-xs-2"><i class="fa fa-fw fa-cog"></i></div>
                                <div class="col-xs-2"><i class="fa fa-fw fa-compass"></i></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-2"><i class="fa fa-fw fa-edit"></i></div>
                                <div class="col-xs-2"><i class="fa fa-fw fa-female"></i></div>
                                <div class="col-xs-2"><i class="fa fa-fw fa-frown-o"></i></div>
                                <div class="col-xs-2"><i class="fa fa-fw fa-legal"></i></div>
                                <div class="col-xs-2"><i class="fa fa-fw fa-mail-reply-all"></i></div>
                                <div class="col-xs-2"><i class="fa fa-fw fa-mail-forward"></i></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-2"><i class="fa fa-fw fa-phone-square"></i></div>
                                <div class="col-xs-2"><i class="fa fa-fw fa-plus-circle"></i></div>
                                <div class="col-xs-2"><i class="fa fa-fw fa-rss-square"></i></div>
                                <div class="col-xs-2"><i class="fa fa-fw fa-signal"></i></div>
                                <div class="col-xs-2"><i class="fa fa-fw fa-smile-o"></i></div>
                                <div class="col-xs-2"><i class="fa fa-fw fa-spinner"></i></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-2"><i class="fa fa-fw fa-thumbs-o-up"></i></div>
                                <div class="col-xs-2"><i class="fa fa-fw fa-ticket"></i></div>
                                <div class="col-xs-2"><i class="fa fa-fw fa-times removepanel clickable"></i></div>
                                <div class="col-xs-2"><i class="fa fa-fw fa-toggle-down"></i></div>
                                <div class="col-xs-2"><i class="fa fa-fw fa-trash-o"></i></div>
                                <div class="col-xs-2"><i class="fa fa-fw fa-users"></i></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-2"><i class="fa fa-fw fa-copy"></i></div>
                                <div class="col-xs-2"><i class="fa fa-fw fa-list-ul"></i></div>
                                <div class="col-xs-2"><i class="fa fa-fw fa-list-alt"></i></div>
                                <div class="col-xs-2"><i class="fa fa-fw fa-arrow-circle-down"></i></div>
                                <div class="col-xs-2"><i class="fa fa-fw fa-caret-square-o-down"></i></div>
                                <div class="col-xs-2"><i class="fa fa-fw fa-chevron-right"></i></div>
                            </div>
                        </div>
                        <div class="icon_cover text-center">
                            <a href="{{URL::to('fontawesome_icons')}}" class="btn btn-primary">View All</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <h4 class="iconset_name">Glyphicons</h4>
                    <div class="icon_group">
                        <div class="icon_set glyphicon_icons text-center">
                            <div class="row">
                                <div class="col-xs-2"><span class="glyphicon glyphicon-briefcase"></span></div>
                                <div class="col-xs-2"><span class="glyphicon glyphicon-check"></span></div>
                                <div class="col-xs-2"><span class="glyphicon glyphicon-dashboard"></span></div>
                                <div class="col-xs-2"><span class="glyphicon glyphicon-credit-card"></span></div>
                                <div class="col-xs-2"><span class="glyphicon glyphicon-euro"></span></div>
                                <div class="col-xs-2"><span class="glyphicon glyphicon-eye-open"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-2"><span class="glyphicon glyphicon-floppy-open"></span></div>
                                <div class="col-xs-2"><span class="glyphicon glyphicon-fire"></span></div>
                                <div class="col-xs-2"><span class="glyphicon glyphicon-forward"></span></div>
                                <div class="col-xs-2"><span class="glyphicon glyphicon-hd-video"></span></div>
                                <div class="col-xs-2"><span class="glyphicon glyphicon-header"></span></div>
                                <div class="col-xs-2"><span class="glyphicon glyphicon-heart"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-2"><span class="glyphicon glyphicon-globe"></span></div>
                                <div class="col-xs-2"><span class="glyphicon glyphicon-headphones"></span></div>
                                <div class="col-xs-2"><span class="glyphicon glyphicon-off"></span></div>
                                <div class="col-xs-2"><span class="glyphicon glyphicon-plane"></span></div>
                                <div class="col-xs-2"><span class="glyphicon glyphicon-plus"></span></div>
                                <div class="col-xs-2"><span class="glyphicon glyphicon-pushpin"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-2"><span class="glyphicon glyphicon-remove"></span></div>
                                <div class="col-xs-2"><span class="glyphicon glyphicon-retweet"></span></div>
                                <div class="col-xs-2"><span class="glyphicon glyphicon-saved"></span></div>
                                <div class="col-xs-2"><span class="glyphicon glyphicon-sound-6-1"></span></div>
                                <div class="col-xs-2"><span class="glyphicon glyphicon-th-large"></span></div>
                                <div class="col-xs-2"><span class="glyphicon glyphicon-tower"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-2"><span class="glyphicon glyphicon-tree-deciduous"></span></div>
                                <div class="col-xs-2"><span class="glyphicon glyphicon-trash"></span></div>
                                <div class="col-xs-2"><span class="glyphicon glyphicon-user"></span></div>
                                <div class="col-xs-2"><span class="glyphicon glyphicon-warning-sign"></span></div>
                                <div class="col-xs-2"><span class="glyphicon glyphicon-star"></span></div>
                                <div class="col-xs-2"><span class="glyphicon glyphicon-signal"></span></div>
                            </div>
                        </div>
                        <div class="icon_cover text-center">
                            <a href="{{URL::to('glyphicons')}}" class="btn btn-primary">View All</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 m-t-10">
                    <h4 class="iconset_name">Simple Line Icons</h4>
                    <div class="icon_group">
                        <div class="icon_set text-center">
                            <div class="row">
                                <div class="col-xs-2"><i class="icon-compass icons"></i></div>
                                <div class="col-xs-2"><i class="icon-directions icons"></i></div>
                                <div class="col-xs-2"><i class="icon-earphones-alt icons"></i></div>
                                <div class="col-xs-2"><i class="icon-equalizer icons"></i></div>
                                <div class="col-xs-2"><i class="icon-dislike icons"></i></div>
                                <div class="col-xs-2"><i class="icon-mustache icons"></i></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-2"><i class="icon-cursor-move icons"></i></div>
                                <div class="col-xs-2"><i class="icon-folder icons"></i></div>
                                <div class="col-xs-2"><i class="icon-ghost icons"></i></div>
                                <div class="col-xs-2"><i class="icon-present icons"></i></div>
                                <div class="col-xs-2"><i class="icon-grid icons"></i></div>
                                <div class="col-xs-2"><i class="icon-social-linkedin icons"></i></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-2"><i class="icon-symbol-female icons"></i></div>
                                <div class="col-xs-2"><i class="icon-social-behance icons"></i></div>
                                <div class="col-xs-2"><i class="icon-settings icons"></i></div>
                                <div class="col-xs-2"><i class="icon-paper-plane icons"></i></div>
                                <div class="col-xs-2"><i class="icon-lock icons"></i></div>
                                <div class="col-xs-2"><i class="icon-camrecorder icons"></i></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-2"><i class="icon-magnifier-remove icons"></i></div>
                                <div class="col-xs-2"><i class="icon-calendar icons"></i></div>
                                <div class="col-xs-2"><i class="icon-control-play icons"></i></div>
                                <div class="col-xs-2"><i class="icon-social-twitter icons"></i></div>
                                <div class="col-xs-2"><i class="icon-social-facebook icons"></i></div>
                                <div class="col-xs-2"><i class="icon-social-dropbox icons"></i></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-2"><i class="icon-social-vkontakte icons"></i></div>
                                <div class="col-xs-2"><i class="icon-social-google icons"></i></div>
                                <div class="col-xs-2"><i class="icon-cloud-upload icons"></i></div>
                                <div class="col-xs-2"><i class="icon-control-rewind icons"></i></div>
                                <div class="col-xs-2"><i class="icon-size-fullscreen icons"></i></div>
                                <div class="col-xs-2"><i class="icon-diamond icons"></i></div>
                            </div>
                        </div>
                        <div class="icon_cover text-center">
                            <a href="{{URL::to('simple_line_icons')}}" class="btn btn-primary">View All</a>
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
    <!-- end of page level js -->
@stop
