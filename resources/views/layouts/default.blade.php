<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>
        Admin Portal | @section('title') @show
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- global css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/unauthorize-notify.css')}}"/>
    <link href="{{asset('assets/vendors/toastr/css/toastr.min.css')}}" rel="stylesheet"/>
    <style type="text/css">
        .k-grid table{
            font-size: 10px !important;
            font-weight: bold !important;
        }
        .sub-menu {
          padding-left: 20px !important;
          list-style: none;
          padding: 0;
          margin: 0;
        }

        #menu > ul > li > .sub-menu > li > a {
          padding-left: 0px !important;
        }

    </style>
@yield('header_styles')
<!-- end of global css -->
</head>
<?php
    use App\Http\Traits\PermissionTrait;
    use App\Module;
    use App\Menus;
    use App\Helpers\LAHelper;
    $permissions = PermissionTrait::getUserPermissions();
    $merchantTypeInfo = PermissionTrait::getMerchantType();
    $MerchantType = $merchantTypeInfo->merchant_type_id;
    $environmentData = PermissionTrait::getCurrentEnvironment();
    if(isset($environmentData->environment_name)){
        $environment = $environmentData->environment_name;
    }else{
        $environment = \Request::getHttpHost();
    }
?>
<body class="skin-coreplus" bsurl="{{ url('') }}">
<div class="preloader">
    <div class="loader_img"><img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64"></div>
</div>
<!-- header logo: style can be found in header-->
<header class="header">
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="index " class="logo">
            <!-- Add the class icon to your logo image or logo icon to add the margining -->
            <!-- <img src="{{asset('assets/img/logo.png')}}" alt="logo"/> -->
            <h2> Admin Portal </h2>
        </a>
        <!-- Header Navbar: style can be found in header-->
        <!-- Sidebar toggle button-->
        <!-- Sidebar toggle button-->
        <div>
            <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button"> <i
                        class="fa fa-bars"></i>
            </a>
        </div>
        <div class="navbar-right">
            <ul class="nav navbar-nav">

                <!-- Notifications: style can be found in dropdown-->
                <li class="dropdown notifications-menu">
                    <a id="activity_notification" href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o black"></i>
                        <span class="label label-warning">{!! $totalActivity !!}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li class="dropdown-title">You have {!! $totalActivity !!} new notifications</li>
                        @foreach($hase_activities as $key => $hase_activity)
                            <li>
                                <div class="message icon-not striped-col">
                                    <div class="message-body">
                                        {!! $hase_activity->message !!}
                                        <br>
                                        <span class="text-danger">
                                            <span style="font-size:12px;font-weight: bold;">
                                                <?php
                                                echo date('h:i A',strtotime($hase_activity->date_added)); 
                                                ?>
                                            </span>
                                            <span style="font-size:9px">
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
                        <li class="dropdown-footer">
                            <a id="activity_url" href="{!!url('hase_activity')!!}"> View All Activities</a>
                        </li>
                        <input type = 'hidden' name = '_token' value='{{Session::token()}}'>
                        <input type = 'hidden' id='DefaultStatusUpdatePath' value="{{url('')}}">
                    </ul>
                </li>
                <!-- User Account: style can be found in dropdown-->

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle padding-user" data-toggle="dropdown">
                        <img src="{{asset('assets/img/authors/avatar.jpg')}}" width="35"
                             class="img-circle img-responsive pull-left"
                             height="35" alt="User Image">
                        <div class="riot">
                            <div>
                                {{ Auth::user()->username }}
                                <span>
                                        <i class="caret"></i>
                                    </span>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu" style="width: 230px">
                        <!-- User image -->
                        <!-- <li class="user-header">
                            <img src="{{asset('assets/img/authors/avatar.jpg')}}" class="img-circle" alt="User Image">
                            <p> {{ Auth::user()->username }}</p>
                        </li> -->
                        <!-- Menu Body -->
                        <!-- <li class="p-t-3"><a href="{{ URL :: to('#') }}"> <i class="fa fa-user"></i> My Profile </a> -->
                        <li class="p-t-3">
                            <div style="height: 40px;float: left;padding: 6px;">{{ $environment}} :</div><iframe src="{{asset('version.txt')}}" width=150 height=40 frameborder=0 ></iframe>
                        </li>
                        
                        <li class="user-footer">
                            <div class="pull-right">
                                <a href="{{ url('logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out"></i>
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ url('logout') }}" method="GET" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<!-- For horizontal menu -->
@yield('horizontal_header')
<!-- horizontal menu ends -->
<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side sidebar-offcanvas">
        <!-- sidebar: style can be found in sidebar-->
        <section class="sidebar">
            <div id="menu" role="navigationA">
                <div class="nav_profile">
                    <div class="media profile-left">
                        <a class="pull-left profile-thumb" href="#">
                            <img src="{{asset('assets/img/authors/avatar.jpg')}}" class="img-circle" alt="User Image">
                        </a>
                        <div class="content-profile">
                            <h4 class="media-heading">
                                {{ Auth::user()->username }}
                            </h4>
                        </div>
                    </div>
                </div>
                <ul class="navigation">
                    <?php
                    $menuItems = Menus::where("parent", 0)->orderBy('hierarchy', 'asc')->get();
                    $active_module=Request::segment(1);
                    if(Request::segment(2)!==null){
                        $active_module=Request::segment(1)."/".Request::segment(2);
                    }
                    ?>
                    @foreach ($menuItems as $menu)
                        <?php echo LAHelper::print_menu($menu,$permissions,$active_module); ?>
                    @endforeach
                </ul>
                <!-- / .navigation -->
            </div>
            <!-- menu -->
        </section>
        <!-- /.sidebar -->
    </aside>
    <aside class="right-side">
        @if(session()->has('message'))
            <div class="note note-danger">
                <i class="fa fa-warning fa-2x pull-left"></i>
                <span>{{ session()->get('message') }}</span>
            </div>
        @endif

        <!-- Content -->
        @yield('content')

    </aside>
    <!-- page wrapper-->
</div>
<!-- wrapper-->
<!-- global js -->

<script src="{{asset('assets/js/app.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/config.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/vendors/toastr/js/toastr.min.js')}}"></script>
<script src="{{asset('assets/js/custom_js/Hase_layout.js')}}" type="text/javascript"></script>
<script src="{{ asset('la-assets/plugins/jquery-validation/jquery.validate.min.js') }}" type="text/javascript"></script>
<!-- <script type="text/javascript">
    var orderId = localStorage.getItem('assetQueueOrderIdentity');
    var defaultBasePath = $("#DefaultStatusUpdatePath").val();
    var token = $('input[name="_token"]').val();
    console.log(orderId);
    if(orderId)
    {
        $.ajax({
           type:'POST',
           data:{_token:token,order_id:orderId,status_id:6},
           url : defaultBasePath+"/update_queue_status",
           error:function(xhr,status,error) {
               console.log(error);
           },
           success:function(assetTypeListResponse,status,xhr) {
               localStorage.setItem('assetQueueOrderIdentity', '');
           }

        });
    }
</script> -->
<!-- end of global js -->
@yield('footer_scripts')


<!-- end page level js -->
</body>

</html>
