@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
        Draggable Portlets
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css"  href="{{asset('assets/css/portlet.css')}}"/>
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Draggable Portlets
            </h1>
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
                        Draggable Portlets
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row ui-sortable" id="sortable_portlets">
                <div class="col-md-4 sortable">
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
                        <div class="portlet-title bg-primary">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Portlet
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div>
                                Tom loves Canada. Angela and Tom met. Angela and Tom want to play. Angela and Tom want
                                to jump. Angela and Tom want to yell. Angela and Tom play, jump and yell.
                            </div>
                        </div>
                    </div>
                    <!-- END Portlet PORTLET-->
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
                        <div class="portlet-title bg-success">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Portlet
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div>
                                Tom loves Canada. Angela and Tom met. Angela and Tom want to play. Angela and Tom want
                                to jump. Angela and Tom want to yell. Angela and Tom play, jump and yell.
                            </div>
                        </div>
                    </div>
                    <!-- END Portlet PORTLET-->
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
                        <div class="portlet-title bg-info">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Portlet
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div>
                                Tom loves Canada. Angela and Tom met. Angela and Tom want to play. Angela and Tom want
                                to jump. Angela and Tom want to yell. Angela and Tom play, jump and yell.
                            </div>
                        </div>
                    </div>
                    <!-- END Portlet PORTLET-->
                </div>
                <div class="col-md-4 sortable">
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
                        <div class="portlet-title bg-danger">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Portlet
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div>
                                Tom loves Canada. Angela and Tom met. Angela and Tom want to play. Angela and Tom want
                                to jump. Angela and Tom want to yell. Angela and Tom play, jump and yell.
                            </div>
                        </div>
                    </div>
                    <!-- END Portlet PORTLET-->
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
                        <div class="portlet-title bg-info">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Portlet
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div>
                                Tom loves Canada. Angela and Tom met. Angela and Tom want to play. Angela and Tom want
                                to jump. Angela and Tom want to yell. Angela and Tom play, jump and yell.
                            </div>
                        </div>
                    </div>
                    <!-- END Portlet PORTLET-->
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
                        <div class="portlet-title bg-warning">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Portlet
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div>
                                Tom loves Canada. Angela and Tom met. Angela and Tom want to play. Angela and Tom want
                                to jump. Angela and Tom want to yell. Angela and Tom play, jump and yell.
                            </div>
                        </div>
                    </div>
                    <!-- END Portlet PORTLET-->
                </div>
                <div class="col-md-4 sortable">
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
                        <div class="portlet-title bg-warning">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Portlet
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div>
                                Tom loves Canada. Angela and Tom met. Angela and Tom want to play. Angela and Tom want
                                to jump. Angela and Tom want to yell. Angela and Tom play, jump and yell.
                            </div>
                        </div>
                    </div>
                    <!-- END Portlet PORTLET-->
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
                        <div class="portlet-title bg-primary">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Portlet
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div>
                                Tom loves Canada. Angela and Tom met. Angela and Tom want to play. Angela and Tom want
                                to jump. Angela and Tom want to yell. Angela and Tom play, jump and yell.
                            </div>
                        </div>
                    </div>
                    <!-- END Portlet PORTLET-->
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box notsort">
                        <div class="portlet-title bg-danger">
                            <div class="caption">
                                <i class="fa fa-fw fa-exclamation"></i> Non Draggable Portlet
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div>
                                Tom loves Canada. Angela and Tom met. Angela and Tom want to play. Angela and Tom want
                                to jump. Angela and Tom want to yell. Angela and Tom play, jump and yell.
                            </div>
                        </div>
                    </div>
                    <!-- END Portlet PORTLET-->
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
<script type="text/javascript" src="{{asset('assets/js/custom_js/draggable_portlets.js')}}" ></script>
    <!-- end of page level js -->
@stop
