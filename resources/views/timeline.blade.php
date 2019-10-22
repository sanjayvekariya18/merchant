@extends('layouts/default')

{{-- Page title --}}
@section('title')
    TimeLine
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/animate/animate.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/timeline.css')}}"/>
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <!--section starts-->
        <h1>TimeLine</h1>
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
                Timeline
            </li>
        </ol>
    </section>
    <!--section ends-->
    <!--section ends-->
    <section class="content">
        <!--main content-->
        <div class="row">
            <div class="col-md-12 timeline_panel">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-fw fa-clock-o"></i> Timeline
                        </h3>
                        <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                    </div>
                    <div class="panel-body">
                        <!--timeline-->
                        <div>
                            <ul class="timeline">
                                <li>
                                    <div class="timeline-badge primary wow lightSpeedIn center">
                                        <i class="fa fa-fw fa-floppy-o"></i>
                                    </div>
                                    <div class="timeline-panel wow slideInLeft" style="display:inline-block;">
                                        <div class="timeline-heading">
                                            <h4 class="timeline-title">We are a multi national company now</h4>
                                            <p>
                                                <small class="text-primary">11 hours ago via Twitter</small>
                                            </p>
                                        </div>
                                        <div class="timeline-body">
                                            <p>
                                                Lorem Ipsum is simply dummy, vidis lio, in elementis mé pra quem é
                                                amistosis quis leo..
                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li class="timeline-inverted">
                                    <div class="timeline-badge danger wow lightSpeedIn center">
                                        <i class="fa fa-fw fa-check-square-o"></i>
                                    </div>
                                    <div class="timeline-panel wow slideInRight">
                                        <div class="timeline-heading">
                                            <h4 class="timeline-title">We won the best website award</h4>
                                            <p>
                                                <small class="text-danger">May 08, 2016</small>
                                            </p>
                                        </div>
                                        <div class="timeline-body">
                                            <p>Lorem Ipsum is simply dummy, vidis litro abertis.</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="timeline-badge info wow lightSpeedIn center">
                                        <i class="fa fa-fw fa-credit-card"></i>
                                    </div>
                                    <div class="timeline-panel wow slideInLeft">
                                        <div class="timeline-heading">
                                            <h4 class="timeline-title">Hired our first employee</h4>
                                            <p>
                                                <small class="text-info">June 10, 2005</small>
                                            </p>
                                        </div>
                                        <div class="timeline-body">
                                            <p>
                                                Lorem Ipsum is simply dummy, vidis litro abertis. Consetis
                                                adipiscings elitis. Pra uium u num gostis.
                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li class="timeline-inverted">
                                    <div class="timeline-badge warning wow lightSpeedIn center">
                                        <i class="fa fa-fw fa-indent"></i>
                                    </div>
                                    <div class="timeline-panel wow lightSpeedIn">
                                        <div class="timeline-heading">
                                            <h4 class="timeline-title">Rented office space</h4>
                                            <p>
                                                <small class="text-warning">Jan 05, 2002</small>
                                            </p>
                                        </div>
                                        <div class="timeline-body">
                                            <p>
                                                Lorem Ipsum is simply dummy, vidis litro abertis. Cais bolis eu num
                                                gostis.
                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="timeline-badge default wow lightSpeedIn center">
                                        <i class="fa fa-fw fa-thumbs-o-up"></i>
                                    </div>
                                    <div class="timeline-panel wow slideInLeft">
                                        <div class="timeline-heading">
                                            <h4 class="timeline-title">Planning to open an office</h4>
                                            <p>
                                                <small class="text-default-gray">jan 02, 2017</small>
                                            </p>
                                        </div>
                                        <div class="timeline-body">
                                            <p>
                                                Lorem Ipsum is simply dummy, vidis litro abertis. Consetis
                                                adipiscings elitis. Pra lá , depois divoltis porris, s mé pra quem é
                                                amistosis.
                                            </p>
                                            <hr>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle"
                                                        data-toggle="dropdown">
                                                    <i class="glyphicon glyphicon-cog"></i>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        <a href="#">Action</a>
                                                    </li>
                                                    <li>
                                                        <a href="#">Another action</a>
                                                    </li>
                                                    <li>
                                                        <a href="#">Something else here</a>
                                                    </li>
                                                    <li class="divider"></li>
                                                    <li>
                                                        <a href="#">Separated link</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <!--timeline ends-->
                    </div>
                </div>
            </div>
        </div>
        <!--row end-->
        <!--main content ends-->
        @include('layouts.right_sidebar')
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
    <script type="text/javascript" src="{{asset('assets/vendors/wow/js/wow.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/time_line.js')}}"></script>
    <!-- end of page level js -->
@stop
