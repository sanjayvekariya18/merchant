@extends('layouts/default')
{{-- Page title --}}
@section('title')
Account List
@parent
@stop
{{-- page level styles --}}
@section('header_styles')
    <style>
        table thead tr{
            background-color: #d9ecf5;
            color: #003f59
        }
        table tr th,table tr td{
            width: 25%;
        }
        span.k-error{
            color: red;
        }     

        .k-confirm {
            top: 300px !important;
        }
        
    </style>
    
    <script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.blueopal.min.css">

@stop

@section('content')
    <section class="content-header">
        <h1>Social</h1>
        <ol class="breadcrumb">
            <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
            <li><a href="active"> Social</a></li>
        </ol>
    </section>
    <section class="content">
        <section class="content p-l-r-15">
            <div class="row">
                <div class="panel panel-primary ">
                    <div class="panel-heading">
                        <h4 class="panel-title">    
                            <i class="fa fa-fw fa-users"></i> Social
                        </h4>
                    </div>
                    <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                    <div class="panel-body" style="background-color: #d9ecf5">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="tabstrip">
                                    <ul>
                                        <li class="k-state-active">Social</li>
                                        <li>Social Api Keys</li>
                                    </ul>
                                    <div id="tab1">
                                        <form method='POST' action='{!!url("social")!!}' id="socialForm">
                                            {{ csrf_field() }}

                                            <div class="table-responsive">
                                                <table class = "table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Social Code</th>
                                                            <th>Social Name</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <input id="social_code" name="social_code" class="form-control k-textbox" required validationMessage="Social Code Required"/>
                                                            </td>
                                                            <td>
                                                                <input id="social_name" name="social_name" class="form-control k-textbox" required validationMessage="Social Name Required"/>
                                                            </td>
                                                            <td>
                                                                <button type="submit" id="socialsubmitBtn" class="send-btn k-button">Create Social</button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div id="socialGrid"></div>
                                        </form>
                                    </div>
                                    <div id="tab2">
                                        <form method='POST' action='{!!url("social")!!}' id="socialApiKeysForm">
                                            {{ csrf_field() }}
                                            <table class = "table table-bordered" style = 'background:#fff'>
                                                <thead>
                                                    <tr>
                                                        <th>Identity Name</th>
                                                        <th>Account Name</th>
                                                        <th>Wallet Name</th>
                                                        <th>Social Name</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <input id="person_id" name="person_id" />
                                                        </td>
                                                        <td>
                                                            <select id="account_id" name="account_id"></select>
                                                        </td>
                                                        <td>
                                                            <select id="wallet_id" name="wallet_id" class="wallet-box"></select>
                                                            <button class="k-button k-plus addwallet" style="display: none"><i class="k-icon k-i-plus"></i></button>
                                                        </td>
                                                        <td>
                                                            <select id="social_id" name="social_id"></select>
                                                            <button class="k-button login" name="login"> Login </button>
                                                        </td>
                                                        
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="manual">
                                                <table class = "table table-bordered" style = 'background:#fff'>
                                                    <thead>
                                                        <tr>
                                                            <th>API Key Name</th>
                                                            <th>Connector Key</th>
                                                            <th>Connector Passcode</th>
                                                            <th>Consumer Key</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <input id="apikey_name" name="apikey_name" class="form-control k-textbox" required validationMessage="API Key Required"/>
                                                            </td>
                                                            <td>
                                                                <input id="connector_key" name="connector_key" class="form-control k-textbox" required validationMessage="Connector Key Required"/>
                                                            </td>
                                                            <td>
                                                                <input id="connector_passcode" name="connector_passcode" class="form-control k-textbox" required validationMessage="Connector Passcode Required"/>
                                                            </td>
                                                            <td>
                                                                <input id="consumer_key" name="consumer_key" class="form-control k-textbox"/>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>  
                                                <table class = "table table-bordered" style = 'background:#fff'>
                                                    <thead>
                                                        <tr>
                                                            <th>Consumer Secret</th>
                                                            <th>Access Token</th>
                                                            <th>Access Secret</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <input id="consumer_secret" name="consumer_secret" class="form-control k-textbox"/>
                                                            </td>
                                                            <td>
                                                                <input id="access_token" name="access_token" class="form-control k-textbox" required validationMessage="Access Token Required"/>
                                                            </td>
                                                            <td>
                                                                <input id="access_secret" name="access_secret" class="form-control k-textbox" required validationMessage="Access Secret Required"/>
                                                            </td>
                                                            <td></td> 
                                                        </tr>
                                                    </tbody>
                                                </table>    
                                            </div>
                                            <button type="submit" id="socialApiKeysSubmitBtn" class="send-btn k-button" style="margin-bottom: 15px;">Create Social Api Keys</button>

                                            <div id="socialApiKeysGrid"></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
        </section>
        <!-- form-modal -->
            <div id="top_modal" class="modal fade animated position_modal" role="dialog">
                <div class="modal-dialog" style="width: 76% !important">
                    <div class="modal-content" style="background-color: #d9ecf5">
                        <div class="modal-header" style="background-color: #13688c;color: white">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"></h4>
                        </div>

                        <div class="panel panel-primary" style="border-color: snow;">
                            <div class="panel-body" >
                                <form method='POST' action="{{url('account/createAccountWallet')}}" id="walletForm">
                                    {{ csrf_field() }}
                                    <table class = "table table-bordered" style = 'background:#fff;'>
                                        <thead>
                                            <tr>
                                                <th style="width: 30%">Wallet Name</th>
                                                <th style="width: 70%">Wallet Address</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="text" class="k-textbox" id="wallet_name" name="wallet_name" placeholder="Wallet Name" style="width: 100%" required validationMessage="Name Required" />
                                                </td>
                                                <td>
                                                    <input type="text" class="k-textbox" id="wallet_address" name="wallet_address" placeholder="Wallet Address" style="width: 100%" required validationMessage="Address Required"/>
                                                    <span class="k-error"></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class = "table table-bordered" style = 'background:#fff;'>
                                        <thead>
                                            <tr>
                                                <th></th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr style="text-align: center">
                                                <td>
                                                    <button type="submit" id="w_submitBtn" class="send-btn k-button">Create Wallet</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <input type="hidden" id="wallet_account_id" name="account_id" value="" />
                                </form>
                                <div id="AccountWalletListGrid"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- form-modal end -->
    </section>
@endsection
<script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
@section('footer_scripts')
    <script src="assets/kendoui/js/kendo.all.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/SocialAccountWallet.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/Social.js')}}"></script>
@stop