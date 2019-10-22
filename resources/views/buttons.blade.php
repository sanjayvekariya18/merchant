@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Buttons
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/hover/css/hover-min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/buttons_sass.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/advbuttons.css')}}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <!--section starts-->
        <h1>Buttons</h1>
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
                Buttons
            </li>
        </ol>
    </section>
    <section class="content">
        <!--main content-->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-fw fa-gear"></i> 2D Transforms Flat Buttons
                        </h3>
                        <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                    </div>
                    <div class="panel-body">
                        <div class="flatbuttons">
                            <div class="text-left">
                                <h5>Radiused Buttons</h5>
                                <ul>
                                    <li>
                                        <button class="button button-rounded button-flat hvr-buzz">Buzz</button>
                                    </li>
                                    <li>
                                        <button class="button button-rounded button-primary-flat hvr-hang">Hang
                                        </button>
                                    </li>
                                    <li>
                                        <button class="button button-rounded button-action-flat hvr-sink">Sink
                                        </button>
                                    </li>
                                    <li>
                                        <button class="button button-rounded button-highlight-flat hvr-pop">Pop
                                        </button>
                                    </li>
                                    <li>
                                        <button class="button button-rounded button-caution-flat hvr-float">Float
                                        </button>
                                    </li>
                                    <li>
                                        <button class="button button-rounded button-royal-flat hvr-rotate">Rotate
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <div class="text-left btn-rounded">
                                <h5>Rounded Buttons</h5>
                                <ul>
                                    <li>
                                        <button class="button button-pill button-flat hvr-grow">
                                            Grow Button
                                        </button>
                                    </li>
                                    <li>
                                        <button class="button button-pill button-primary-flat hvr-shrink">
                                            Shrink Button
                                        </button>
                                    </li>
                                    <li>
                                        <button class="button button-pill button-action-flat hvr-pulse">
                                            Pulse Button
                                        </button>
                                    </li>
                                    <li>
                                        <button class="button button-pill button-highlight-flat hvr-pulse-grow">
                                            Pulse-grow
                                        </button>
                                    </li>
                                    <li>
                                        <button class="button button-pill button-caution-flat hvr-pulse-shrink">
                                            Pulse-shrink
                                        </button>
                                    </li>
                                    <li>
                                        <button class="button button-pill button-royal-flat hvr-push">
                                            Push Button
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <div class="text-left">
                                <h5>Rectangle Box</h5>
                                <ul>
                                    <li>
                                        <button class="button button-flat hvr-wobble-skew">Button</button>
                                    </li>
                                    <li>
                                        <button class="button button-primary-flat hvr-wobble-bottom">Button</button>
                                    </li>
                                    <li>
                                        <button class="button button-action-flat hvr-wobble-to-top-right">Button
                                        </button>
                                    </li>
                                    <li>
                                        <button class="button button-highlight-flat hvr-wobble-vertical">Button
                                        </button>
                                    </li>
                                    <li>
                                        <button class="button button-caution-flat hvr-wobble-horizontal">Button
                                        </button>
                                    </li>
                                    <li>
                                        <button class="button button-royal-flat hvr-skew-backward">Button</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="text-left">
                                <h5>Circled Buttons</h5>
                                <ul>
                                    <li>
                                        <button class="button button-circle button-flat hvr-wobble-top">Button
                                        </button>
                                    </li>
                                    <li>
                                        <button class="button button-circle button-primary-flat hvr-skew">Button
                                        </button>
                                    </li>
                                    <li>
                                        <button class="button button-circle button-action-flat hvr-wobble-to-top-right">
                                            Button
                                        </button>
                                    </li>
                                    <li>
                                        <button class="button button-circle button-highlight-flat hvr-pulse-grow">
                                            Button
                                        </button>
                                    </li>
                                    <li>
                                        <button class="button button-circle button-caution-flat hvr-grow">Button
                                        </button>
                                    </li>
                                    <li>
                                        <button class="button button-circle button-royal-flat hvr-buzz-out">Button
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-fw fa-bell-o"></i> Shadow and Glow Transition Buttons
                        </h3>
                        <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                    </div>
                    <div class="panel-body">
                        <div class="flatbuttons_small">
                            <ul>
                                <li>
                                    <button class="button button-glow button-rounded button-flat hvr-float-shadow">
                                        Button
                                    </button>
                                </li>
                                <li>
                                    <button class="button button-glow button-rounded button-primary-flat hvr-float-shadow">
                                        Button
                                    </button>
                                </li>
                                <li>
                                    <button class="button button-glow button-rounded button-action-flat hvr-float-shadow">
                                        Button
                                    </button>
                                </li>
                                <li>
                                    <button class="button button-glow button-rounded button-highlight-flat hvr-float-shadow">
                                        Button
                                    </button>
                                </li>
                                <li>
                                    <button class="button button-glow button-rounded button-caution-flat hvr-float-shadow">
                                        Button
                                    </button>
                                </li>
                                <li>
                                    <button class="button button-glow button-rounded button-royal-flat hvr-float-shadow">
                                        Button
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-fw fa-rocket"></i> Quick Shortcuts
                        </h3>
                        <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                    </div>
                    <div class="panel-body">
                        <div>
                            <div class="col-xs-6 col-md-6">
                                <button class="btn btn-danger btn-sm btn-responsive" role="button">
                                    <span class="glyphicon glyphicon-list-alt"></span>
                                    <br/> Apps
                                </button>
                                <button class="btn btn-warning btn-sm btn-responsive" role="button">
                                    <span class="glyphicon glyphicon-bookmark"></span>
                                    <br/> Bookmarks
                                </button>
                                <button class="btn btn-primary btn-sm btn-responsive" role="button">
                                    <span class="glyphicon glyphicon-signal"></span>
                                    <br/> Reports
                                </button>
                                <button class="btn btn-primary btn-sm btn-responsive" role="button">
                                    <span class="glyphicon glyphicon-comment"></span>
                                    <br/> Comments
                                </button>
                            </div>
                            <div class="col-xs-6 col-md-6">
                                <button class="btn btn-success btn-sm btn-responsive" role="button">
                                    <span class="glyphicon glyphicon-user"></span>
                                    <br/> Users
                                </button>
                                <button class="btn btn-info btn-sm btn-responsive" role="button">
                                    <span class="glyphicon glyphicon-file"></span>
                                    <br/> Notes
                                </button>
                                <button class="btn btn-primary btn-sm btn-responsive" role="button">
                                    <span class="glyphicon glyphicon-picture"></span>
                                    <br/> Photos
                                </button>
                                <button class="btn btn-primary btn-sm btn-responsive" role="button">
                                    <span class="glyphicon glyphicon-tag"></span>
                                    <br/> Tags
                                </button>
                            </div>
                        </div>
                        <!--</div>-->
                        <!--</div>-->
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <button class="btn btn-success btn-lg btn-block" role="button">
                                    <span class="glyphicon glyphicon-globe"></span> Website
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-fw fa-rocket"></i> 3-D Buttons
                        </h3>
                        <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                    </div>
                    <div class="panel-body">
                        <div class="flatbuttons">
                            <ul>
                                <li>
                                    <button class="button button-3d">Button</button>
                                </li>
                                <li>
                                    <button class="button button-3d button-primary button-rounded btn_3d">Button
                                    </button>
                                </li>
                                <li>
                                    <button class="button button-3d button-action button-pill btn_3d">Button
                                    </button>
                                </li>
                                <li>
                                    <button class="button button-3d button-highlight button-circle btn_3d">Button
                                    </button>
                                </li>
                                <li>
                                    <button class="button button-3d button-caution icon-btn btn_3d">
                                        <i class="fa fa-camera"></i> Button
                                    </button>
                                </li>
                                <li>
                                    <button class="button button-3d button-royal btn_3d">Button</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-fw fa-gear"></i> Buttons
                        </h3>
                        <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                    </div>
                    <div class="panel-body">
                        <div class="flatbuttons">
                            <div class="row">
                                <!-- Example Icon Button -->
                                <div class="col-md-12">
                                    <h5 class="example-title">Icon Button & Dropdown</h5>
                                    <div class="example example-buttons">
                                        <button type="button" class="btn btn-icon btn-default m-r-50"><i
                                                    class="icon fa fa-fw fa-map-marker" aria-hidden="true"></i></button>
                                        <button type="button" class="btn btn-icon btn-primary m-r-50"><i
                                                    class="icon fa fa-fw fa-th" aria-hidden="true"></i></button>
                                        <button type="button" class="btn btn-icon btn-success m-r-50"><i
                                                    class="icon fa fa-fw fa-bell" aria-hidden="true"></i></button>
                                        <button type="button" class="btn btn-icon btn-info m-r-50"><i
                                                    class="icon fa fa-fw fa-calendar" aria-hidden="true"></i></button>
                                        <button type="button" class="btn btn-icon btn-warning btn-round m-r-50"><i
                                                    class="icon fa fa-fw fa-clock-o" aria-hidden="true"></i></button>
                                        <button type="button" class="btn btn-icon btn-danger btn-round m-r-50"><i
                                                    class="icon fa fa-fw fa-puzzle-piece" aria-hidden="true"></i>
                                        </button>
                                        <div class="btn-group drop_btn" role="group">
                                            <button type="button" class="btn btn-primary dropdown-toggle m-r-50"
                                                    id="exampleIconDropdown1" data-toggle="dropdown"
                                                    aria-expanded="false">
                                                <i class="icon fa fa-fw fa-calendar" aria-hidden="true"></i>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="exampleIconDropdown1"
                                                role="menu">
                                                <li role="presentation"><a href="javascript:void(0)"
                                                                           role="menuitem">Dropdown link</a></li>
                                                <li role="presentation"><a href="javascript:void(0)"
                                                                           role="menuitem">Dropdown link</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Example Icon Button -->
                            </div>
                            <div class="row">
                                <div class="col-md-12 ">
                                    <!-- Example Icon Dropdown -->
                                    <div class="example-wrap">
                                        <h5 class="example-title">Button Animation</h5>
                                        <div class="btn-group" role="group">
                                            <button type="button"
                                                    class="btn btn-animate btn-animate-side btn-success m-r-50">
                                                <span><i class="icon fa fa-fw fa-download" aria-hidden="true"></i>Side Animation</span>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-animate btn-animate-vertical btn-success m-r-50">
                                                        <span><i class="icon fa fa-fw fa-download"
                                                                 aria-hidden="true"></i>Vertical
                                                                      Animation</span>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- End Example Icon Dropdown -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-fw fa-bullhorn"></i> Group Buttons
                        </h3>
                        <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                    </div>
                    <div class="panel-body">
                        <div>
                            <div class="col-md-12 text-center">
                                <div class="ui-group-buttons">
                                    <a href="#" class="btn btn-success" role="button">
                                        <span class="glyphicon glyphicon-thumbs-up"></span>
                                    </a>
                                    <div class="or"></div>
                                    <a href="#" class="btn btn-danger" role="button">
                                        <span class="glyphicon glyphicon-thumbs-down"></span>
                                    </a>
                                </div>
                                <div class="ui-group-buttons">
                                    <a href="#" class="btn btn-success" role="button">
                                        <span class="glyphicon glyphicon-floppy-disk"></span>
                                    </a>
                                    <div class="or"></div>
                                    <a href="#" class="btn btn-danger" role="button">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </a>
                                </div>
                                <br/>
                                <br/>
                                <div class="ui-group-buttons">
                                    <a href="#" class="btn btn-success" role="button">
                                        <span class="glyphicon glyphicon-ok"></span> Sign Up
                                    </a>
                                    <div class="or"></div>
                                    <a href="#" class="btn btn-default" role="button">
                                        <span class="glyphicon glyphicon-remove"></span> Reset
                                    </a>
                                </div>
                                <br/>
                                <br/>
                                <div class="ui-group-buttons">
                                    <button type="button" class="btn btn-primary btn-lg">Large</button>
                                    <div class="or or-lg"></div>
                                    <button type="button" class="btn btn-success btn-lg">Large</button>
                                </div>
                                <br/>
                                <br/>
                                <div class="ui-group-buttons">
                                    <button type="button" class="btn btn-primary">Default</button>
                                    <div class="or"></div>
                                    <button type="button" class="btn btn-success">Success</button>
                                </div>
                                <br/>
                                <br/>
                                <div class="ui-group-buttons">
                                    <button type="button" class="btn btn-primary btn-sm">
                                        Small
                                    </button>
                                    <div class="or or-sm"></div>
                                    <button type="button" class="btn btn-success btn-sm">
                                        Small
                                    </button>
                                </div>
                                <br/>
                                <br/>
                                <div class="ui-group-buttons">
                                    <button type="button" class="btn btn-primary btn-xs">
                                        Extra small
                                    </button>
                                    <div class="or or-xs"></div>
                                    <button type="button" class="btn btn-success btn-xs">
                                        Extra small
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-fw fa-clock-o"></i> Buttons With Labels
                        </h3>
                        <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                    </div>
                    <div class="panel-body">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <button type="button" class="btn btn-labeled btn-success">
                                                <span class="btn-label">
                                                <i class="glyphicon glyphicon-ok"></i>
                                            </span> Success
                                    </button>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <button type="button" class="btn btn-labeled btn-danger">
                                                <span class="btn-label">
                                                <i class="glyphicon glyphicon-remove"></i>
                                            </span> Cancel
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <button type="button" class="btn btn-labeled btn-warning">
                                                <span class="btn-label">
                                                <i class="glyphicon glyphicon-bookmark"></i>
                                            </span> Bookmark
                                    </button>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <button type="button" class="btn btn-labeled btn-primary">
                                                <span class="btn-label">
                                                <i class="glyphicon glyphicon-camera"></i>
                                            </span> Camera
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <button type="button" class="btn btn-labeled btn-default">
                                                <span class="btn-label">
                                                <i class="glyphicon glyphicon-chevron-left"></i>
                                            </span> Left
                                    </button>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <button type="button" class="btn btn-labeled btn-default">
                                        Right <span class="btn-label label-right">
                                                <i class="glyphicon glyphicon-chevron-right"></i>
                                            </span>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <button type="button" class="btn btn-labeled btn-success">
                                                <span class="btn-label">
                                                        <i class="fa fa-fw fa-thumbs-o-up"></i>
                                                    </span> Up
                                    </button>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <button type="button" class="btn btn-labeled btn-warning">
                                                <span class="btn-label">
                                                <i class="glyphicon glyphicon-thumbs-down"></i>
                                            </span> Down
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <button type="button" class="btn btn-labeled btn-info">
                                                <span class="btn-label">
                                                <i class="glyphicon glyphicon-refresh"></i>
                                            </span> Refresh
                                    </button>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <button type="button" class="btn btn-labeled btn-primary">
                                                <span class="btn-label">
                                                <i class="glyphicon glyphicon-trash"></i>
                                            </span> Trash
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <a class="btn btn-warning btn-labeled" role="button">
                                                <span class="btn-label">
                                                <i class="glyphicon glyphicon-info-sign"></i>
                                            </span> Info Web
                                    </a>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <a class="btn btn-success btn-labeled" role="button">
                                                <span class="btn-label">
                                                <i class="glyphicon glyphicon-globe"></i>
                                            </span> Web
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--main content ends-->
        @include('layouts.right_sidebar')
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
    <script type="text/javascript" src="{{asset('assets/vendors/Buttons/js/buttons.js')}}"></script>
    <!--end of page level js-->
@stop