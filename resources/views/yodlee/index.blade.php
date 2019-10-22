@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Bank Account Details
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
@section('header_styles')
    <!--page level css -->
    <script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.blueopal.min.css">
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Account Details</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Bank Account Details
        </li>
    </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> Accounts
                    </h4>
                </div>
                <div class="panel-body">
                    <div id="yodleeDetailsError" style="display:none;">
                        <div class="note note-danger">
                            <i class="fa fa-warning fa-2x pull-left"></i>
                            <span></span>
                        </div>
                    </div>
                    <div class="preloader" style="background: none !important; ">
                        <div class="loader_img">
                           <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
                        </div>
                    </div>
                    <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                    <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                    <div class="row">
                        <div class="col-md-12">
                            <?php if(!$yodlee_login): ?>
                                <button type="button" id="loginWithYodlee" name="loginWithYodlee" class="send-btn k-button">Login with Bank</button>
                            <?php elseif($yodlee_login && $tokenExpire): ?>
                                <div id="invalidLogin">
                                    <div class="note note-danger">
                                        <i class="fa fa-warning fa-2x pull-left"></i>
                                        <span>Token Expire.please click on below button to Reauthenticate</span>
                                    </div>
                                </div>
                                <button type="button" id="loginWithYodlee" name="loginWithYodlee" class="send-btn k-button">Re Authenticate</button>
                            <?php else :?>
                                <button type="button" id="loginWithYodleeFastLink" name="loginWithFastLinkYodlee" class="send-btn k-button">Account Linking</button>
                                <input type="hidden" name="cob_session" id="cob_session" value="{{$yodlee_login->cob_session}}">
                                <input type="hidden" name="user_session" id="user_session" value="{{$yodlee_login->user_session}}">
                                <input type="hidden" name="access_token" id="access_token" value="{{$yodlee_login->access_token}}">
                                <input type="hidden" name="app_id" id="app_id" value="{{$yodlee_login->app_id}}">
                                <button type="button" id="syncYodleeAccount" name="syncYodleeAccount" class="send-btn k-button">sync Accounts</button>
                                <button type="button" id="syncAccountTransaction" name="syncAccountTransaction" class="send-btn k-button">sync Account Transactions</button>
                            <?php endif; ?>
                            <br>
                            <br>
                            <div id="yodleeAccountGrid"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" data-backdrop="static" data-keyboard="false" id="loginWithYodleeModel" tabindex="-1" role="dialog" aria-labelledby="Heading" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="loginPreloader" style="background: none !important;display: none;">
                        <div class="loader_img">
                           <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64" style="display: none;">
                        </div>
                    </div>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title custom_align" id="Heading">Login with Bank</h4>
                    </div>
                    <div class="modal-body">
                        <div id="invalidLogin" style="display:none;">
                            <div class="note note-danger">
                                <i class="fa fa-warning fa-2x pull-left"></i>
                                <span></span>
                            </div>
                        </div>
                        <form id="yodleeLoginForm" class="form-horizontal" method="POST" enctype='application/json'>
                            <div class="form-group">
                                <label for="yodleeCobrandUsername" class="col-md-4 control-label">Cobrand UserName</label>

                                <div class="col-md-6">
                                    <input id="yodleeCobrandUsername" type="text" class="form-control" name="yodleeCobrandUsername" required autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="yodleeCobrandPassword" class="col-md-4 control-label">Cobrand Password</label>

                                <div class="col-md-6">
                                    <input id="yodleeCobrandPassword" type="password" class="form-control" name="yodleeCobrandPassword" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="yodleeUsername" class="col-md-4 control-label"> UserName</label>

                                <div class="col-md-6">
                                    <input id="yodleeUsername" type="text" class="form-control" name="yodleeUsername" required autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="yodleeUserPassword" class="col-md-4 control-label">User Password</label>

                                <div class="col-md-6">
                                    <input id="yodleeUserPassword" type="password" class="form-control" name="yodleeUserPassword" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" id="yodleeLogin" class="btn btn-primary">
                                        Login with Bank
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
        </div>
        <div class="modal fade" data-backdrop="static" data-keyboard="false" id="loginWithFastLinkYodleeModel" tabindex="-1" role="dialog" aria-labelledby="Heading" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title custom_align" id="Heading">Account FastLink</h4>
                    </div>
                    <div class="modal-body">
                        <div id="invalidLogin" style="display:none;">
                            <div class="note note-danger">
                                <i class="fa fa-warning fa-2x pull-left"></i>
                                <span></span>
                            </div>
                        </div>
                        <?php if($yodlee_login && !$tokenExpire): ?>
                            <iframe style="width: 100%;height: 500px" src="{{url('yodlee/fastLinkLoginFrom')}}"></iframe>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
        </div>
    </section>    
</section>

@endsection
{{-- page level scripts --}}
@section('footer_scripts')
<script src="assets/kendoui/js/kendo.all.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/yodlee.js')}}"></script>
<!-- begining of page level js -->
<script type="text/javascript">
    @if(Session::has('type'))
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
        var $toast = toastr["{{ Session::pull('type') }}"]("", "{{ Session::pull('msg') }}");
    @endif

</script>
<script type="text/x-kendo-template" id="transactionDetailsTemplate">
    <div class="transactionDetails"></div>
</script>
@stop