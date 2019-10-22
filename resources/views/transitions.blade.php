@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Transitions
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/animate/animate.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/animate1.css')}}"/>
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <!--section starts-->
        <h1>Transitions</h1>
        <ol class="breadcrumb">
            <li>
                <a href="index">
                    <i class="fa fa-fw fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="#">UI Components</a>
            </li>
            <li class="active">
                Transitions
            </li>
        </ol>
    </section>
    <!--section ends-->
    <section class="content">
        <!--main content-->
        <div class="row animated fadeInDown">
            <!--row starts-->
            <div class="col-md-12">
                <!--md starts-->
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-fw fa-bell-o"></i> Transition Effects
                        </h3>
                        <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="row text-center">
                                <div class="col-md-12">
                                    <h5> Click on the Icon to see an Animation</h5>
                                </div>
                            </div>
                            <div class="panel-body text-center">
                                <!--content starts-->
                                <div class="wrap">
                                    <div class="row ">
                                        <div class="col-md-2 col-sm-12">
                                            <div id="tad" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Tada</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <div id="fls" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Flash</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <div id="shk" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Shake</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <div id="sw" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Swing</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <div id="pul" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Pulse</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <div id="wobb" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Wobble</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--first row end-->
                                    <!--second row start-->
                                    <div class="row ">
                                        <div class="col-md-2">
                                            <div id="jell" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Jello</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="rubber" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Rubber Band</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="bi" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Bounce In</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="bid" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Bounce In Down</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="bil" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Bounce In Left</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="bir" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Bounce In Right</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--second row end-->
                                    <!--third row start-->
                                    <div class="row ">
                                        <div class="col-md-2">
                                            <div id="bo" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Bounce Out</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="bol" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Bounce Out Left</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="bor" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Bounce Out Right</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="bod" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Bounce Out Down</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="fi" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>FadeIn</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="fid" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>FadeIn Down</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--third row end-->
                                    <!--fourth row start-->
                                    <div class="row ">
                                        <div class="col-md-2">
                                            <div id="filb" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>FadeIn LeftBig</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="firb" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>FadeIn RightBig</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="fo" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>FadeOut</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="fol" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>FadeOut Left</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="for" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>FadeOut Right</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="fou" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>FadeOut Up</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--fourth row end-->
                                    <!--fifth row start-->
                                    <div class="row ">
                                        <div class="col-md-2">
                                            <div id="flp" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Flip</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="fix" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>FlipInX</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="fiy" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>FlipInY</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="fox" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>FlipOutX</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="foy" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>FlipOutY</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="lis" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Light Speed In</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--fifth row end-->
                                    <!--sixth row start-->
                                    <div class="row ">
                                        <div class="col-md-2">
                                            <div id="roi" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Rotate In</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="rou" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Rotate Out</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="lso" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>light Speed Out</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="riur" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Rotate In Up Right</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="riul" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Rotate In Up Left</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="roul" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Rotate Out Up Left</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--sixth row end-->
                                    <!--seventh row start-->
                                    <div class="row ">
                                        <div class="col-md-2">
                                            <div id="siu" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Slide In Up</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="sid" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Slide In Down</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="sil" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Slide In Left</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="sir" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Slide In Right</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="sou" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Slide Out Up</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="sor" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Slide Out Right</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--seventh row end-->
                                    <!--eight row start-->
                                    <div class="row ">
                                        <div class="col-md-2">
                                            <div id="zi" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Zoom In</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="zo" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Zoom Out</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="ziu" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Zoom In Up</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="hi" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Hinge</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="ri" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Roll In</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div id="ro" class="panel  panel-default">
                                                <div class="panel-body">
                                                    <!-- Animation trigger-->
                                                    <em class="fa fa-eject fa-2x"></em>
                                                    <p>Roll Out</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--eight row end-->
                                </div>
                            </div>
                        </div>
                    </div> <!-- panel body ending-->
                </div>
            </div>
        </div>
        <!--row ends-->
        <!--main content ends-->
        @include('layouts.right_sidebar')
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
    <script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/transitions_custom.js')}}" ></script>
    <!-- end of page level js -->
@stop
