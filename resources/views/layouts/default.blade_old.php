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
    </style>
@yield('header_styles')
<!-- end of global css -->
</head>
<?php
    use App\Http\Traits\PermissionTrait;
    $permissions = PermissionTrait::getUserPermissions();
    $merchantTypeInfo = PermissionTrait::getMerchantType();
    $MerchantType = $merchantTypeInfo->merchant_type_id;
    /*echo "<pre>";
    print_r($MerchantType);
    die;*/
?>
<body class="skin-coreplus">
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
                            <div style="height: 40px;float: left;padding: 6px;">Version :</div><iframe src="{{asset('version.txt')}}" width=150 height=40 frameborder=0 ></iframe>
                        </li>
                        
                        <li class="user-footer">
                            <div class="pull-right">
                                <a href="{{ url('logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out"></i>
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
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
                    
                    <li {!! ( Request::is('index') || Request::is('home') || Request::is('/') || Request::is('activity') || Request::is('maps') ? 'class="active"' : "") !!}>
                        <a href="#">
                            <i class="fa fa-user"></i>
                            <span>Dashboard</span> <span
                                    class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li {!! ( Request::is('index') || Request::is('home') || Request::is('/') || Request::is('activity') ? 'class="active"':"") !!}>
                                <a href="{{ URL::to('activity') }} ">
                                    <i class="fa fa-home"></i>
                                    <span class="mm-text ">Activity History</span>
                                </a>
                            </li>
                            <li {!! ( Request::is('maps') ? 'class="active"':"") !!}>
                                <a href="{{ URL::to('maps') }} ">
                                    <i class="fa fa-home"></i>
                                    <span class="mm-text ">Location Map</span>
                                </a>
                            </li>
                        </ul>
                    </li>                    

                    <?php if (array_key_exists("Hase_approval",$permissions)){ ?>
                        <li {!! (Request::is('hase_approval')? 'class="active"':"") !!}>
                            <a href="{{URL::to('hase_approval')}} ">
                                <i class="fa fa-list" aria-hidden="true"></i>
                                <span>Approval Queue</span>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (array_key_exists("Hase_otc_order_entry",$permissions) || array_key_exists("Position_view",$permissions) || array_key_exists("Hase_trade_orders_queue",$permissions) || array_key_exists("Asset_move",$permissions) || array_key_exists("Trade_breach",$permissions)|| array_key_exists("Asset_pnl",$permissions)|| array_key_exists("Trade_status_type",$permissions)|| array_key_exists("Trade_order_type",$permissions)|| array_key_exists("Trade_reason_type",$permissions)|| array_key_exists("Trade_side_type",$permissions)|| array_key_exists("Status_crypto_type",$permissions)|| array_key_exists("Asset",$permissions)|| array_key_exists("Exchange_asset_list",$permissions)|| array_key_exists("Asset_team_list",$permissions)|| array_key_exists("Asset_social_list",$permissions)|| array_key_exists("Asset_sale_list",$permissions)|| array_key_exists("Asset_type",$permissions)|| array_key_exists("Asset_rate",$permissions)|| array_key_exists("Exchange_rates",$permissions)|| array_key_exists("Exchange_asset_list",$permissions)|| array_key_exists("Exchange",$permissions)|| array_key_exists("Exchange_language_list",$permissions)|| array_key_exists("Trade_fee_schedule",$permissions)|| array_key_exists("Trade_limits",$permissions)|| array_key_exists("Asset_fund",$permissions)|| array_key_exists("Asset_flow",$permissions)|| array_key_exists("Payee",$permissions)|| array_key_exists("Tax_type",$permissions)
                        || array_key_exists("Asset_team_list/assets",$permissions)|| array_key_exists("Payment_summary",$permissions)|| array_key_exists("Transaction_summary",$permissions)|| array_key_exists("Social",$permissions)|| array_key_exists("Asset_team",$permissions)|| array_key_exists("Yodlee",$permissions)|| array_key_exists("Bank",$permissions)|| array_key_exists("Trade_transaction_type",$permissions)){ ?>

                    <li {!! ( Request::is('hase_otc_order_entry') || Request::is('position_view') || Request::is('hase_trade_orders_queue') || Request::is('asset_move') || Request::is('trade_breach') || Request::is('asset_pnl') || Request::is('position_view') || Request::is('trade_status_type') || Request::is('trade_order_type') || Request::is('trade_reason_type') || Request::is('trade_side_type') || Request::is('status_operations_type') || Request::is('status_fiat_type') || Request::is('status_crypto_type') || Request::is('asset') || Request::is('exchange_asset_list') || Request::is('asset_team_list') || Request::is('asset_social_list') || Request::is('asset_sale_list') || Request::is('asset_type') || Request::is('asset_rate') || Request::is('exchange') || Request::is('exchange_asset_list') || Request::is('exchange_rates') || Request::is('exchange_language_list') || Request::is('trade_fee_schedule') || Request::is('trade_limits') || Request::is('asset_fund') || Request::is('asset_flow') || Request::is('payee') || Request::is('tax_type') || Request::is('asset_team_list/assets') || Request::is('payment_summary') || Request::is('Transaction_summary') || Request::is('social') || Request::is('asset_team') || Request::is('yodlee') || Request::is('bank') || Request::is('trade_transaction_type') ? 'class="active"' : "") !!}>

                        <a href="#">
                            <i class="fa fa-user"></i>
                            <span>Trade Manager</span> <span
                                    class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                             <?php if (array_key_exists("Hase_otc_order_entry",$permissions)){ ?>
                            <li {!! (Request::is('hase_otc_order_entry')? 'class="active"':"") !!}>
                                <a href="{{URL::to('hase_otc_order_entry')}} ">
                                    <i class="fa fa-list" aria-hidden="true"></i>
                                    Order Entry
                                </a>
                            </li>   
                        <?php } ?>
                            <?php if (array_key_exists("Position",$permissions)){ ?>
                                <li {!! (Request::is('position_view')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('position_view')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i>
                                        Trade Positions
                                    </a>
                                </li>
                            <?php } ?>   

                            <?php if (array_key_exists("Asset_fund",$permissions)){ ?>
                            <li {!! (Request::is('asset_fund')? 'class="active"':"") !!}>
                                <a href="{{URL::to('asset_fund')}} ">
                                    <i class="fa fa-list" aria-hidden="true"></i>
                                    Asset Funding
                                </a>
                            </li>
                            <?php } ?>

                            <li {!!( Request::is('hase_trade_orders_queue') || Request::is('asset_move') || Request::is('trade_breach') || Request::is('asset_pnl') || Request::is('trade_limits') || Request::is('asset_flow') ? 'class="active"':"")!!}>
                                <a href="#">
                                    <i class="fa fa-user"></i>
                                    Compliance
                                    <span class="fa arrow"></span>
                                </a>
                                <ul class="sub-menu sub-submenu">
                                     <?php if (array_key_exists("Hase_trade_orders_queue",$permissions)) { ?>
                                    <li {!! ( Request::is('hase_trade_orders_queue')? 'class="active"':"") !!}>
                                        <a href="{{URL::to('hase_trade_orders_queue')}} ">
                                            <i class="fa fa-list" aria-hidden="true"></i> Order Review 
                                        </a>
                                   </li>
                                   <?php } ?>
                                   <?php if (array_key_exists("Asset_move",$permissions)) { ?>
                                        <li {!! (Request::is('asset_move')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('asset_move')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i> Asset Move
                                            </a>
                                        </li>
                                    <?php } ?>

                                    <?php if (array_key_exists("Asset_pnl",$permissions)){ ?>
                                        <li {!! (Request::is('asset_pnl')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('asset_pnl')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i> Trade P&L
                                            </a>
                                        </li>
                                    <?php } ?>

                                    <?php if (array_key_exists("Trade_breach",$permissions)){ ?>
                                        <li {!! (Request::is('trade_breach')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('trade_breach')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i>
                                                Trade Breach
                                            </a>
                                        </li>
                                    <?php } ?>

                                    <?php if (array_key_exists("Asset_flow",$permissions)) { ?>
                                        <li {!! (Request::is('asset_flow')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('asset_flow')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i> Asset Flow
                                            </a>
                                        </li>
                                    <?php } ?>

                                    <?php if (array_key_exists("Trade_limits",$permissions)){ ?>
                                        <li {!! (Request::is('trade_limits')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('trade_limits')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i>
                                                Trade Limits
                                            </a>
                                        </li>
                                    <?php } ?>                                    
                                   
                                </ul>
                            </li>      

                            <?php if (array_key_exists("Transaction_summary",$permissions)){ ?>
                                <li {!! (Request::is('transaction_summary/summary_view')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('transaction_summary/summary_view')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i> Transactions
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if (array_key_exists("Payment_summary",$permissions)){ ?>
                            <li {!! ( Request::is('payment_summary')? 'class="active"':"") !!}>
                                <a href="{{URL::to('payment_summary')}} ">
                                    <i class="fa fa-list" aria-hidden="true"></i> Payment
                                </a>
                           </li>
                            <?php } ?>
                            <li {!!(Request::is('trade_status_type') || Request::is('trade_order_type') || Request::is('trade_reason_type') || Request::is('trade_side_type') || Request::is('status_operations_type') || Request::is('status_fiat_type') || Request::is('status_crypto_type') || Request::is('trade_transaction_type') ? 'class="active"':"")!!}>
                                <a href="#">
                                    <i class="fa fa-user"></i>
                                    Trade Attribute
                                    <span class="fa arrow"></span>
                                </a>
                                <ul class="sub-menu sub-submenu">
                                    <?php if (array_key_exists("Trade_status_type",$permissions)){ ?>
                                        <li {!! (Request::is('trade_status_type')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('trade_status_type')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i> Trade Status Type
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (array_key_exists("Trade_order_type",$permissions)){ ?>
                                        <li {!! (Request::is('trade_order_type')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('trade_order_type')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i> Trade Order Type
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (array_key_exists("Trade_reason_type",$permissions)){ ?>
                                        <li {!! (Request::is('trade_reason_type')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('trade_reason_type')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i> Trade Reason Type
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (array_key_exists("Trade_side_type",$permissions)){ ?>
                                            <li {!! (Request::is('trade_side_type')? 'class="active"':"") !!}>
                                                <a href="{{URL::to('trade_side_type')}} ">
                                                    <i class="fa fa-list" aria-hidden="true"></i> Trade Side Type
                                                </a>
                                            </li>
                                    <?php } ?>
                                    <?php if (array_key_exists("Trade_transaction_type",$permissions)){ ?>
                                        <li {!! (Request::is('trade_transaction_type')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('trade_transaction_type')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i> Trade Transaction Type
                                            </a>
                                        </li>
                                    <?php } ?> 
                                    <?php if (array_key_exists("Status_operations_type",$permissions)){ ?>
                                        <li {!! (Request::is('status_operations_type')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('status_operations_type')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i> Status Operations Type
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (array_key_exists("Status_fiat_type",$permissions)){ ?>
                                        <li {!! (Request::is('status_fiat_type')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('status_fiat_type')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i> Status Fiat Type
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (array_key_exists("Status_crypto_type",$permissions)){ ?>
                                        <li {!! (Request::is('status_crypto_type')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('status_crypto_type')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i> Status Crypto Type
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>

                            <li {!!( Request::is('asset') || Request::is('asset_team_list') || Request::is('asset_social_list') || Request::is('asset_sale_list') || Request::is('asset_type') || Request::is('asset_rate') || Request::is('asset_team') || Request::is('asset_team_list/assets') ? 'class="active"':"")!!}>
                                <a href="#">
                                    <i class="fa fa-user"></i>
                                    Asset
                                    <span class="fa arrow"></span>
                                </a>
                                <ul class="sub-menu sub-submenu">
                                    
                                    <?php if (array_key_exists("Asset",$permissions)){ ?>
                                        <li {!! (Request::is('asset')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('asset')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i> Asset Global
                                            </a>
                                        </li>
                                    <?php } ?>

                                    <?php if (array_key_exists("Asset_rate",$permissions)) { ?>
                                        <li {!! (Request::is('asset_rate')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('asset_rate')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i>
                                                Asset Rates
                                            </a>
                                        </li>
                                    <?php } ?>

                                    <li>
                                        <a href="#">
                                            <i class="fa fa-list" aria-hidden="true"></i>
                                            Asset Categories
                                        </a>
                                    </li>

                                    <?php if (array_key_exists("Asset_team",$permissions)) { ?>
                                        <li {!! (Request::is('asset_team')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('asset_team')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i>
                                                Asset Team Project
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (array_key_exists("Asset_team_list",$permissions)) { ?>
                                        <li {!! (Request::is('asset_team_list')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('asset_team_list')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i>
                                                Asset Team
                                            </a>
                                        </li>
                                    <?php } ?>

                                    <?php if (array_key_exists("Asset_team_list",$permissions)) { ?>
                                        <li {!! (Request::is('asset_team_list/assets')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('asset_team_list/assets')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i>
                                                Asset People
                                            </a>
                                        </li>
                                    <?php } ?>

                                    <?php if (array_key_exists("Asset_social_list",$permissions)) { ?>
                                        <li {!! (Request::is('asset_social_list')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('asset_social_list')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i>
                                                Asset Socials
                                            </a>
                                        </li>
                                    <?php } ?>

                                    <?php if (array_key_exists("Asset_sale_list",$permissions)) { ?>
                                        <li {!! (Request::is('asset_sale_list')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('asset_sale_list')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i>
                                                Asset Sales
                                            </a>
                                        </li>
                                    <?php } ?>

                                    <?php if (array_key_exists("Asset_type",$permissions)){ ?>
                                        <li {!! (Request::is('asset_type')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('asset_type')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i> Asset Types
                                            </a>
                                        </li>
                                    <?php } ?>

                                </ul>
                            </li>   

                            <li {!!( Request::is('exchange') || Request::is('exchange_asset_list') || Request::is('exchange_rates') || Request::is('exchange_language_list') || Request::is('trade_fee_schedule') ? 'class="active"':"")!!}>
                                <a href="#">
                                    <i class="fa fa-user"></i>
                                    Exchange
                                    <span class="fa arrow"></span>
                                </a>
                                <ul class="sub-menu sub-submenu">
                                    
                                    <?php if (array_key_exists("Exchange",$permissions)){ ?>
                                        <li {!! (Request::is('exchange')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('exchange')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i> Exchange Global
                                            </a>
                                        </li>
                                    <?php } ?>

                                    <?php if (array_key_exists("Exchange_asset_list",$permissions)) { ?>
                                        <li {!! (Request::is('exchange_asset_list')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('exchange_asset_list')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i>
                                                Exchange Assets
                                            </a>
                                        </li>
                                    <?php } ?>

                                    <?php if (array_key_exists("Exchange_rate",$permissions)){ ?>
                                        <li {!! (Request::is('exchange_rates')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('exchange_rates')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i> Exchange Asset Rates
                                            </a>
                                        </li>
                                    <?php } ?>

                                    <?php if (array_key_exists("Exchange_language_list",$permissions)) { ?>
                                        <li {!! (Request::is('exchange_language_list')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('exchange_language_list')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i>
                                                Exchange Languages
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (array_key_exists("Trade_fee_schedule",$permissions)){ ?>
                                        <li {!! (Request::is('trade_fee_schedule')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('trade_fee_schedule')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i> Exchange Fee Schedule
                                            </a>
                                        </li>
                                    <?php } ?>
                                    
                                </ul>
                                <!-- Exchange End -->
                            </li>

                            <?php if (array_key_exists("Payee",$permissions)) { ?>
                                <li {!! (Request::is('payee')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('payee')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i> Payee
                                    </a>
                                </li>
                            <?php } ?>

                            <?php if (array_key_exists("Tax_type",$permissions)) { ?>
                                <li {!! (Request::is('tax_type')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('tax_type')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i> Tax Types
                                    </a>
                                </li>
                            <?php } ?>

                            <?php if (array_key_exists("Social",$permissions)) { ?>
                                <li {!! (Request::is('social')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('social')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i> Social
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if (array_key_exists("Bank",$permissions)) { ?>
                                <li {!! (Request::is('bank')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('bank')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i> Bank
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if (array_key_exists("Yodlee",$permissions)) { ?>
                                <li {!! (Request::is('yodlee')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('yodlee')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i> Bank Account
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                        <?php } ?>
                        <!-- Trade manager End -->

                        <!-- Merchant Details -->

                        <?php if (array_key_exists("Hase_merchant",$permissions) || array_key_exists("Merchant_city_list",$permissions) || array_key_exists("Hase_promotion",$permissions) || array_key_exists("Hase_category_list",$permissions) || array_key_exists("Hase_style_list",$permissions)){ ?>

                            <li {!! (Request::is ('hase_merchant') || Request::is ('hase_promotion') || Request::is ('hase_category_list') || Request::is ('hase_style_list') ? 'class="active"':"" )!!}>
                                <a href="#">
                                    <i class="fa fa-users"></i>
                                    <span>Merchant Details</span>
                                    <span class="fa arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <?php if (array_key_exists("Hase_merchant",$permissions)){ ?>
                                        <li {!! (Request::is('hase_merchant')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('hase_merchant')}} ">
                                                <i class="fa fa-users" aria-hidden="true"></i>
                                                <span>
                                                <?php echo (session('merchantId') == 0) ? 
                                                        "Merchants":"Basic Information" ?>
                                                </span>
                                            </a>
                                        </li>
                                    <?php } ?>                                    
                                    <?php //if (array_key_exists("Hase_location",$permissions)){ ?>
                                        <!-- <li {!! (Request::is('hase_location')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('hase_location')}} ">
                                                <i class="fa fa-map-marker"></i> Locations
                                            </a>
                                        </li> -->
                                    <?php //} ?>
                                    <?php if (array_key_exists("Hase_promotion",$permissions)){ ?>
                                        <li {!! (Request::is('hase_promotion')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('hase_promotion')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i>
                                                <span>Promotions</span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (array_key_exists("Hase_category_list",$permissions)){ ?>
                                        <li {!! (Request::is('hase_category_list')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('hase_category_list')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i> Tag Categories
                                            </a>
                                         </li>
                                    <?php } ?>
                                    <?php if (array_key_exists("Hase_style_list",$permissions)){ ?>
                                        <li {!! (Request::is('hase_style_list')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('hase_style_list')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i>
                                                <?php if(session('merchantId') == 0) {
                                                        echo "Tag Styles";
                                                    } else {
                                                        if($MerchantType == 8)
                                                            echo "Cuisines";
                                                        else
                                                            echo "Industries";
                                                    }

                                                ?>
                                            </a>
                                         </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>

                        <!-- Merchant Details End -->

                        <!-- Users -->
                        <?php if (array_key_exists("Hase_customer",$permissions) || array_key_exists("Hase_customer_group",$permissions) || array_key_exists("Hase_staff",$permissions) || array_key_exists("Hase_staff_group",$permissions) || array_key_exists("Account",$permissions)){ ?>
                            <li {!! (Request::is('hase_customer')||Request::is('hase_customer_group') ||Request::is('hase_staff') ||Request::is('hase_staff_group')||Request::is('account') ? 'class="active"':"") !!}>
                                <a href="#">
                                    <i class="fa fa-user"></i>
                                    <span>Users</span> <span
                                            class="fa arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <?php if(env('customer_tab_display') <= env('future')): ?>
                                        <?php if (array_key_exists("Hase_customer",$permissions)){ ?>
                                            <li {!! (Request::is('hase_customer')? 'class="active"':"") !!}>
                                                <a href="{{URL::to('hase_customer')}} ">
                                                    <i class="fa fa-list" aria-hidden="true"></i> Customers
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php endif; ?>
                                    <!-- <?php //if(env('customer_group_tab_display') <= env('future')): ?>
                                        <?php //if (array_key_exists("Hase_customer_group",$permissions)){ ?>
                                            <li {!! (Request::is('hase_customer_group')? 'class="active"':"") !!}>
                                                <a href="{{URL::to('hase_customer_group')}} ">
                                                    <i class="fa fa-list" aria-hidden="true"></i> Customer Groups
                                                </a>
                                            </li>
                                        <?php //} ?>
                                    <?php //endif; ?> -->
                                    <?php if (array_key_exists("Hase_staff",$permissions)){ ?>
                                        <li {!! (Request::is('hase_staff')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('hase_staff')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i> Staff
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (array_key_exists("Hase_staff_group",$permissions)){ ?>
                                         <li {!! (Request::is('hase_staff_group')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('hase_staff_group')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i> Roles
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (array_key_exists("Account",$permissions)){ ?>
                                        <li {!! (Request::is('account')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('account')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i>
                                                Accounts
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <!-- Users Ends -->

                        <!-- Events -->
                        <?php
                        if(env('exhibition_status_display') <= env('future')) {
                            if (array_key_exists("Hase_exhibition",$permissions) || array_key_exists("Hase_exhibitor",$permissions) || array_key_exists("Venue",$permissions)){ ?>
                                <li {!! (Request::is('hase_exhibition') || Request::is('hase_exhibitor') || Request::is('venue') ? 'class="active"':"") !!}>
                                    <a href="#">
                                        <i class="fa fa-users" aria-hidden="true"></i>
                                        <span>Events</span>
                                        <span class="fa arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <?php if(env('exhibition_status_display') <= env('future')): ?>
                                            <?php if (array_key_exists("Hase_exhibition",$permissions)){ ?>
                                                <li {!! (Request::is('hase_exhibition')? 'class="active"':"") !!}>
                                                    <a href="{{URL::to('hase_exhibition')}} ">
                                                        <i class="fa fa-list" aria-hidden="true"></i> Exhibition
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        <?php endif; ?>
                                        <?php if(env('exhibitor_status_display') <= env('future')): ?>
                                            <?php if (array_key_exists("Hase_exhibitor",$permissions)){ ?>
                                                <li {!! (Request::is('hase_exhibitor')? 'class="active"':"") !!}>
                                                    <a href="{{URL::to('hase_exhibitor')}} ">
                                                        <i class="fa fa-list" aria-hidden="true"></i> Exhibitor
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        <?php endif; ?>
                                        <?php if(env('venue_status_display') <= env('future')): ?>
                                            <?php if (array_key_exists("Venue",$permissions)){ ?>
                                                <li {!! (Request::is('venue')? 'class="active"':"") !!}>
                                                    <a href="{{URL::to('venue')}} ">
                                                        <i class="fa fa-list" aria-hidden="true"></i> Venue
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        <?php endif; ?>
                                    </ul>
                                </li>
                        <?php } } ?>
                        <!-- Events Ends -->

                        <!-- system -->
                        <?php if (array_key_exists("Hase_permission",$permissions) || array_key_exists("Location",$permissions) || array_key_exists("Hase_working_holiday",$permissions) || array_key_exists("Hase_approval_group_list",$permissions) || array_key_exists("Hase_approval_status",$permissions) || array_key_exists("Hase_approval_crud_status",$permissions) || array_key_exists("Hase_chatbot_communication",$permissions) || array_key_exists("Identity_type",$permissions)){ ?>
                            <li {!! (Request::is('hase_permission') || Request::is ('location') || Request::is ('users_language') || Request::is('hase_working_holiday') || Request::is('hase_approval_group_list') || Request::is('hase_approval_status') || Request::is('hase_chatbot_communication') || Request::is('hase_approval_crud_status') || Request::is('identity_type') ? 'class="active"':"") !!}>
                                <a href="#">
                                    <i class="fa fa-cog"></i>
                                    <span>System</span>
                                     <span class="fa arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <?php if (array_key_exists("Location",$permissions)){ ?>
                                        <li {!! (Request::is('location')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('location')}} ">
                                                <i class="fa fa-users" aria-hidden="true"></i> Locations
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <li {!! (Request::is('users_language')? 'class="active"':"") !!}>
                                        <a href="{{URL::to('users_language')}} ">
                                            <i class="fa fa-list" aria-hidden="true"></i> Language
                                        </a>
                                    </li>
                                    <?php if (array_key_exists("Hase_permission",$permissions)){ ?>
                                        <li {!! (Request::is('hase_permission')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('hase_permission')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i> Permissions
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (array_key_exists("Hase_working_holiday",$permissions)){ ?>
                                        <li {!! (Request::is('hase_working_holiday')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('hase_working_holiday')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i> Holidays
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (array_key_exists("Hase_approval_group_list",$permissions)){ ?>
                                        <li {!! (Request::is('hase_approval_group_list')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('hase_approval_group_list')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i> Approval Routing
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (array_key_exists("Hase_approval_status",$permissions)){ ?>
                                        <li {!! (Request::is('hase_approval_status')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('hase_approval_status')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i> Approval Status
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (array_key_exists("Hase_approval_crud_status",$permissions)){ ?>
                                        <?php
                                            if(env('approval_crud_status_display') <= env('future')) {
                                         ?>
                                                <li {!! (Request::is('hase_approval_crud_status')? 'class="active"':"") !!}>
                                                    <a href="{{URL::to('hase_approval_crud_status')}} ">
                                                        <i class="fa fa-list" aria-hidden="true"></i> Approval CRUD Status
                                                    </a>
                                                </li>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if (array_key_exists("Hase_chatbot_communication",$permissions)){ ?>
                                        <li {!! (Request::is('hase_chatbot_communication')? 'class="active"':"") !!}>
                                            <a href="{{URL::to('hase_chatbot_communication')}} ">
                                                <i class="fa fa-list" aria-hidden="true"></i> Chatbot
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (array_key_exists("Identity_type",$permissions)){ ?>
                                            <li {!! (Request::is('identity_type')? 'class="active"':"") !!}>
                                                <a href="{{URL::to('identity_type')}} ">
                                                    <i class="fa fa-list" aria-hidden="true"></i> Identity Type
                                                </a>
                                            </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <!-- System Ends -->

                        <!-- Shop -->
                        <?php if (array_key_exists("Hase_menu",$permissions) || array_key_exists("Hase_option",$permissions) || array_key_exists("Hase_retail_style_type",$permissions) || array_key_exists("Hase_retail_category_type",$permissions) || array_key_exists("Hase_retail_category_option",$permissions)){ ?>
                            <?php if (session('merchantId') == 0 || $MerchantType == 2 ): ?>

                                <li {!! (Request::is ('hase_product') || Request::is ('hase_product_option') || Request::is ('hase_shop_retail_style_type') ? 'class="active"':"" )!!}>
                                    <a href="#">
                                        <i class="fa fa-cutlery"></i>
                                        <span>
                                                Shops
                                            </span>
                                        <span class="fa arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <?php if (array_key_exists("Hase_menu",$permissions)){ ?>
                                            <li {!! (Request::is('hase_product')? 'class="active"':"") !!}>
                                                <a href="{{URL::to('hase_product')}} ">
                                                    <i class="fa fa-cutlery"></i> Products
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if (array_key_exists("Hase_retail_style_type",$permissions) || array_key_exists("Hase_retail_category_type",$permissions) || array_key_exists("Hase_retail_category_option",$permissions)){ ?>
                                            <li {!! (Request::is('hase_industry_types')? 'class="active"':"") !!}>
                                                <a href="{{URL::to('hase_industry_types')}} ">
                                                    <i class="fa fa-list"></i> Industries
                                                </a>
                                             </li>
                                        <?php } ?>
                                    </ul>
                                </li>
                            <?php endif; ?>
                        <?php } ?>
                        <!-- Shop End -->

                        <!-- Restuarant -->
                        <?php if (array_key_exists("Hase_menu",$permissions) || array_key_exists("Hase_table",$permissions) || array_key_exists("Hase_reservation",$permissions) || array_key_exists("Hase_retail_style_type",$permissions) || array_key_exists("Hase_retail_category_type",$permissions) || array_key_exists("Hase_retail_category_option",$permissions)){ ?>

                            <?php if (session('merchantId') == 0 || $MerchantType == 8 ): ?>
                                <li {!! ( Request::is ('hase_menu') || Request::is ('hase_table') || Request::is ('hase_reservation') || Request::is ('hase_cuisine_types') ? 'class="active"':"" )!!}>
                                    <a href="#">
                                        <i class="fa fa-cutlery"></i>
                                        <span>
                                                Restaurants
                                            </span>
                                        <span class="fa arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <?php if (array_key_exists("Hase_menu",$permissions)){ ?>
                                            <li {!! (Request::is('hase_menu')? 'class="active"':"") !!}>
                                                <a href="{{URL::to('hase_menu')}} ">
                                                    <i class="fa fa-cutlery"></i> Menus
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if (array_key_exists("Hase_retail_style_type",$permissions) || array_key_exists("Hase_retail_category_type",$permissions) || array_key_exists("Hase_retail_category_option",$permissions)){ ?>
                                            <li {!! (Request::is('hase_cuisine_types')? 'class="active"':"") !!}>
                                                <a href="{{URL::to('hase_cuisine_types')}} ">
                                                    <i class="fa fa-list"></i> Cuisines
                                                </a>
                                             </li>
                                        <?php } ?>
                                        <?php if (array_key_exists("Hase_table",$permissions)){ ?>
                                            <li {!! (Request::is('hase_table')? 'class="active"':"") !!}>
                                                <a href="{{URL::to('hase_table')}} ">
                                                    <i class="fa fa-cutlery"></i> Reservation Seating
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if (array_key_exists("Hase_reservation",$permissions)){ ?>
                                            <li {!! (Request::is('hase_reservation')? 'class="active"':"") !!}>
                                                <a href="{{URL::to('hase_reservation')}} ">
                                                    <i class="fa fa-cutlery"></i> Reservations
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </li>
                            <?php endif; ?>

                        <?php } ?>
                        <!-- Restaurant Ends -->

                        <!-- Turk Translation -->
                        <?php if (array_key_exists("Hase_translation_approval",$permissions) || array_key_exists("Hase_translation_queue",$permissions) || array_key_exists("Hase_translation_list",$permissions) || array_key_exists("Hase_word_approval_list",$permissions) || array_key_exists("Hase_translation_view",$permissions)|| array_key_exists("Image_translation",$permissions)|| array_key_exists("Hase_image_upload",$permissions)|| array_key_exists("Hase_image_approval_list",$permissions)|| array_key_exists("Hase_scrape_details_list",$permissions)|| array_key_exists("Hase_scrape_event_approval_list",$permissions)
                        || array_key_exists("Hase_chatbot_communication_view",$permissions)
                        || array_key_exists("Hase_communication_approval_list",$permissions)
                        || array_key_exists("Hase_json_query_view",$permissions)
                        || array_key_exists("Hase_translation_manage",$permissions)
                        || array_key_exists("Hase_status_view_manage",$permissions)){ ?>
                                <li {!! (Request::is('Hase_translation_approval')||Request::is('Hase_translation_queue') ||Request::is('Hase_translation_list') ||Request::is('Hase_word_approval_list')||Request::is('Hase_translation_view') ||Request::is('Image_translation') ||Request::is('Hase_image_upload') ||Request::is('Hase_image_approval_list') ||Request::is('Hase_scrape_details_list')
                                ||Request::is('Hase_chatbot_communication_view')
                                ||Request::is('Hase_communication_approval_list')
                                ||Request::is('Hase_json_query_view')
                                ||Request::is('Hase_scrape_event_approval_list')
                                ||Request::is('Hase_translation_manage')
                                ||Request::is('Hase_status_view_manage')
                                 ? 'class="active"':"") !!}>
                            <a href="#">
                                <i class="fa fa-cog"></i>
                                <span>Turk Translation</span>
                                 <span class="fa arrow"></span>
                            </a>
                           <ul class="sub-menu">
                                 <?php if (array_key_exists("Hase_translation_approval",$permissions)){ ?>       
                                 <li {!! (Request::is('hase_translation_approval')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('hase_translation_approval')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i> Translation Approval
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if (array_key_exists("Hase_translation_queue",$permissions)){ ?>
                                 <li {!! (Request::is('hase_translation_queue')? 'class="active"':"") !!}>
                                   <a href="{{URL::to('hase_translation_queue')}} ">
                                       <i class="fa fa-list" aria-hidden="true"></i> Translation Queue
                                   </a>
                               </li>
                               <?php } ?>
                               <?php if (array_key_exists("Hase_translation_list",$permissions)){ ?>
                                <li {!! (Request::is('hase_translation_list')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('hase_translation_list')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i> Text Only
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if (array_key_exists("Hase_translation_view",$permissions)){ ?>
                                <li {!! (Request::is('hase_translation_view')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('hase_translation_view')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i> Translation View
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if (array_key_exists("Hase_word_approval_list",$permissions)){ ?>
                                <li {!! (Request::is('hase_word_approval_list')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('hase_word_approval_list')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i> Word Approval List
                                    </a>
                                </li>
                                <!-- <li {!! (Request::is('hase_language_translation')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('hase_language_translation')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i> Word Language View
                                    </a>
                                </li> -->
                                <?php } ?>
                                <?php if (array_key_exists("Image_translation",$permissions)){ ?>
                                <li {!! (Request::is('image_translation')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('image_translation')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i> Image
                                    </a>
                                </li> 
                                <?php } ?>
                                <?php if (array_key_exists("Hase_image_upload",$permissions)){ ?>                               
                                <li {!! (Request::is('hase_image_upload')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('hase_image_upload')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i> Image Upload
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if (array_key_exists("Hase_image_approval_list",$permissions)){ ?>
                                <li {!! (Request::is('hase_image_approval_list')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('hase_image_approval_list')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i> Image Approval List
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if (array_key_exists("Hase_scrape_details_list",$permissions)){ ?>
                                <li {!! (Request::is('hase_scrape_details_list')? 'class="active"':"") !!}>
                                   <a href="{{URL::to('hase_scrape_details_list')}} ">
                                       <i class="fa fa-list" aria-hidden="true"></i> Regex History List
                                   </a>
                                </li>
                                <?php } ?>
                                <?php if (array_key_exists("Hase_scrape_event_approval_list",$permissions)){ ?>
                                <li {!! (Request::is('hase_scrape_event_approval_list')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('hase_scrape_event_approval_list')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i> Website Url Approval List
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if (array_key_exists("Hase_chatbot_communication_view",$permissions)){ ?>
                                <li {!! (Request::is('hase_chatbot_communication_view')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('hase_chatbot_communication_view')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i> Communication Translation View
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if (array_key_exists("Hase_communication_approval_list",$permissions)){ ?>
                                <li {!! (Request::is('hase_communication_approval_list')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('hase_communication_approval_list')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i> Communication Approval List
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if (array_key_exists("Hase_json_query_view",$permissions)){ ?>
                                <li {!! (Request::is('hase_json_query_view')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('hase_json_query_view')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i> Json Query
                                    </a>
                                </li>

                                <!-- </li>
                                   <li {!! (Request::is('hase_image_json_query_list')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('hase_image_json_query_list')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i> Image Json Detail
                                    </a>
                                </li> -->
                                <?php } ?>
                                <?php if (array_key_exists("Hase_translation_manage",$permissions)){ ?>
                                 <li {!! (Request::is('hase_translation_manage')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('hase_translation_manage')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i> Translation Manage
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if (array_key_exists("Hase_status_view_manage",$permissions)){ ?>
                                <li {!! (Request::is('hase_status_view_manage')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('hase_status_view_manage')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i> User Status Manage
                                    </a>
                                </li>
                                <?php } ?>
                                <!-- <li {!! (Request::is('hase_chatbot_communication_language_view')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('hase_chatbot_communication_language_view')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i> Communication Language View
                                    </a>
                                </li> -->
                                
                            </ul>
                        </li>
                         <?php } ?>
                        <!-- Turk Translation End -->

                        <!-- Scrapping -->
                        <?php if (array_key_exists("Regex",$permissions) || array_key_exists("Regex_website",$permissions)  || array_key_exists("Regex_result",$permissions)){ ?>

                        <li {!! (Request::is('regex') || Request::is('regex_website') || Request::is('  regex_result') ? 'class="active"':"") !!}>
                            <a href="{{URL::to('regex')}} ">
                                <i class="fa fa-list" aria-hidden="true"></i>
                                <span>Scraping</span>
                                 <span class="fa arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <?php if (array_key_exists("Regex",$permissions)){ ?>
                                <li {!! (Request::is('regex')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('regex')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i> Regex Setup
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if (array_key_exists("Regex_website",$permissions)){ ?>
                                <li {!! (Request::is('regex_website')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('regex_website')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i> Regex Website
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if (array_key_exists("Regex_result",$permissions)){ ?>
                                <li {!! (Request::is('regex_result')? 'class="active"':"") !!}>
                                    <a href="{{URL::to('regex_result')}} ">
                                        <i class="fa fa-list" aria-hidden="true"></i> Regex Result
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php } ?>
                        <!-- Scrapping Ends -->

                    </li>
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
