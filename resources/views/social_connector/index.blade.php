@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Social Connector
    @parent
@stop
<style type="text/css">
    .loginPreloader {
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 100000;
    }
</style>
{{-- page level styles --}}
<?php 
use App\Http\Traits\PermissionTrait;
?>
@section('header_styles')
    <!--page level css -->
    <style type="text/css">
        .customer-photo {
            display: inline-block;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-size: 50px 50px;
            background-position: center center;
            vertical-align: middle;
            line-height: 32px;
            box-shadow: inset 0 0 1px #999, inset 0 0 10px rgba(0,0,0,.2);
            margin-left: 5px;
        }

        .customer-name {
            display: inline-block;
            vertical-align: middle;
            line-height: 32px;
            padding-left: 3px;
        }
    </style>
    <script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.blueopal.min.css">
    <link href="{{ asset('la-assets/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('la-assets/css/ionicons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('la-assets/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Connector Details</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Social Connector
        </li>
    </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> Social Connector
                    </h4>
                </div>
                <div class="panel-body">
                    <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}"/>
                    <input type="hidden" id="requestBaseUrl" value="{!!url('/')!!}"/>
                    <div id="socialConnectorsGrid"></div>                     
                </div>
            </div>
        </div>
    </section>    
</section>
@endsection
{{-- page level scripts --}}
@section('footer_scripts')
<script src="assets/kendoui/js/kendo.all.min.js" type="text/javascript"></script>

<script type="text/javascript" src="{{asset('assets/js/custom_js/google/googleLogin.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/facebook/facebookLogin.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/twitter/twitterLogin.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/linkedin/linkedinLogin.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/github/githubLogin.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/meetup/meetupLogin.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/eventbrite/eventbriteLogin.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/flickr/flickrLogin.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/foursquare/foursquareLogin.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/instagram/instagramLogin.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/strava/stravaLogin.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/weibo/weiboLogin.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/freelancer/freelancerLogin.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/tripit/tripitLogin.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/socialConnectors.js')}}"></script>
<script type="text/javascript">
    function refreshMessage(){

        if (localStorage.getItem("type") !== null && localStorage.getItem("msg") !== null) {
          var type = localStorage.getItem('type');
            var message = localStorage.getItem('msg'); 
            
            localStorage.removeItem('type');
            localStorage.removeItem('msg');

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
            var $toast = toastr[type]("",message); 
        }
          
    } 
</script>    
@stop