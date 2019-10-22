@extends('layouts/default')
{{-- Page title --}}
@section('title')
Database Manager
@parent
@stop
{{-- page level styles --}}
@section('header_styles')
    <style>
        table thead tr{
            background-color: #d9ecf5;
            color: #003f59
        }        
        span.k-error{
            color: red;
        }     

        .k-confirm {
            top: 300px !important;
        }

        .k-grid-edit,.k-grid-delete,.k-grid-update,.k-grid-cancel {
           min-width: 35px !important;
           width: 35px !important;
        }  

        .dd-item > button { display: block; position: relative; cursor: pointer; float: left; width: 25px; height: 20px; margin: 5px 0; padding: 0; text-indent: 100%; white-space: nowrap; overflow: hidden; border: 0; background: transparent; font-size: 12px; line-height: 1; text-align: center; font-weight: bold; margin-left: 0px !important}
        .dd-item > button:before { content: '+'; display: block; position: absolute; width: 100%; text-align: center; text-indent: 0;}
        .dd-item > button[data-action="collapse"]:before { content: '-'; }     

    </style>
    
    <script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.blueopal.min.css">
    <link href="{{ asset('la-assets/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('la-assets/css/ionicons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('la-assets/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />

@stop
<?php
use App\Http\Traits\PermissionTrait;
?>
@section('content')
    <section class="content-header">
        <h1>Database Manager</h1>
        <ol class="breadcrumb">
            <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
            <li><a href="active">Database Manager</a></li>
        </ol>
    </section>
    <section class="content">
        <section class="content p-l-r-15">
            <div class="row">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">    
                            <i class="fa fa-fw fa-users"></i> Database Manager
                        </h4>
                    </div>
                    <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                    <div class="panel-body" style="background-color: #d9ecf5">
                        <div id="tabstrip">
                            <ul>
                                <li class="k-state-active">Database Manager</li>
                                <li class="tab2">Database Menu Priority</li>
                            </ul>
                            <div id="tab1">                                
                                <div id="databaseManagerGrid"></div>
                            </div>                            
                            <div id="tab2" style="overflow: unset">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <button class="collapseAll btn btn-primary btn-sm">Collapse All</button>
                                        <button class="expandAll btn btn-primary btn-sm">Expand All</button>
                                        <div class="dd" id="menu-nestable">
                                            <ol class="dd-list">
                                                
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>            
        </section>        
    </section>
@endsection
<script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
@section('footer_scripts')
<script src="assets/kendoui/js/kendo.all.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/DatabaseManager.js')}}"></script>
<!-- <script type="text/javascript" src="{{asset('assets/js/custom_js/MenusDatabaseManager.js')}}"></script> -->
<script src="{{ asset('assets/js/custom_js/jquery.nestable.js') }}"></script>
<script src="{{ asset('assets/js/custom_js/fontawesome-iconpicker.js') }}"></script>
<script src="{{ asset('la-assets/plugins/stickytabs/jquery.stickytabs.js') }}" type="text/javascript"></script>
<script src="{{ asset('la-assets/plugins/slimScroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
<script>
$(function () {    

    $('input[name=icon]').iconpicker();

    $('#menu-nestable').nestable({
        group: 1
    });

    $('.collapseAll').click(function(){
        $('#menu-nestable button[data-action=collapse]').trigger('click');
    })

    $('.expandAll').click(function(){
        $('#menu-nestable button[data-action=expand]').trigger('click');
    })

    $('#menu-nestable').on('change', function() {
        var jsonData = $('#menu-nestable').nestable('serialize');
        $.ajax({
            url: "{{ url('/database_manager/update_hierarchy') }}",
            method: 'POST',
            data: {
                jsonData: jsonData,
                "_token": '{{ csrf_token() }}'
            },
            success: function( data ) {
                toastr.options = {
                "closeButton": true,
                "positionClass": "toast-top-right",
                "showDuration": "1000",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "swing",
                "showMethod": "show"
            };
            var $toast = toastr[data.status](data.message);
            menus_priority_load();
            }
        });
    });
    
});
</script>
@stop






