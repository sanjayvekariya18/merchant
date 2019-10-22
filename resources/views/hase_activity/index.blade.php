@php use App\Http\Traits\PermissionTrait; @endphp
@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Activities
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/animate/animate.min.css')}}">
    <link href="{{asset('assets/css/custom_css/timeline.css')}}" rel="stylesheet"/>
@stop
@section('content')
<section class="content-header">
    <h1>Activities</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> Users</a>
        </li>
        <li class="active">
            Activities
        </li>
    </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
        <!--main content-->
            <div class="row">
                <div class="col-md-12 timeline_panel">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-clock-o"></i> Activity Log
                            </h3>
                        </div>
                        <div class="panel-body">
                            <!--timeline-->
                            <div>
                                <ul class="timeline">
                                    @foreach($hase_activities as $key => $hase_activity)
                                        <?php $class = ($key % 2 != 0)?"timeline-inverted":"" ?>
                                        <li class="<?php echo $class ?>">
                                            <div class="timeline-badge primary"></div>
                                            <div class="timeline-panel" style="display:inline-block;">
                                                <div class="timeline-heading">
                                                    <h4 class="timeline-title">
                                                    {!! $hase_activity->message !!}
                                                    </h4>
                                                    <span class="text-danger">
                                                        <span style="font-size:15px;font-weight: bold;">
                                                            <?php
                                                            echo date('h:i A',strtotime($hase_activity->date_added)); 
                                                            ?>
                                                        </span>
                                                        <span style="font-size:11px">
                                                            <?php 
                                                                echo PermissionTrait::humanTiming($hase_activity->date_added)
                                                            ?>
                                                            ago
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach 
                                </ul>
                            </div>
                            <!--timeline ends-->
                        </div>
                    </div>
                </div>
            </div>
            <!--main content ends-->
    </section>
</section>
@endsection

{{-- page level scripts --}}
@section('footer_scripts')

<script type="text/javascript" src="{{asset('assets/vendors/wow/js/wow.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/time_line.js')}}"></script>

@stop