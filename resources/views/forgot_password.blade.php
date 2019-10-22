<!DOCTYPE html>
<html>

<head>
    <title>::Admin Forgot Password::</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <!-- Bootstrap -->
    <!-- global level css -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{('assets/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css"/>
    <!-- end of global css-->
    <!-- page level styles-->
    <link href="{{asset('assets/vendors/iCheck/css/all.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/css/forgot_password.css')}}" rel="stylesheet">
    <!-- end of page level styles-->
</head>

<body>
<div class="preloader">
    <div class="loader_img"><img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64"></div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 box animated fadeInUp">
            <div class="text-center"><img src="{{asset('assets/img/pages/logo_gray.png')}}" alt="coreplus logo"></div>
            <h3 class="text-center">Forgot Password
            </h3>
            <p class="text-center enter_email">
                <small>Enter your Registered email</small>
            </p>
            <p class="text-center check_email hidden">
                <small>Check your email for Reset link</small>
            </p>
            <form action="{{URL::to('forgot_password')}}" class="forgot_Form text-center" method="POST" id="forgot_password">
                <div class="form-group">
                    <input type="email" class="form-control email" name="email" id="email" placeholder="Email">
                </div>
                <button type="submit" value="Reset Your Password" class="btn btn-primary btn-block submit-btn">Reset
                    Your Password
                </button>
            </form>
            <div>
                <h4 class="text-primary signup-signin">
                    <a href="{{URL::to('login')}}">Log In</a>
                    <a href="{{URL::to('register')}}" class="pull-right">Sign Up</a>
                </h4>
            </div>
        </div>
    </div>
</div>
<!-- page level js -->
<script src="{{asset('assets/js/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/forgot_password.js')}}" type="text/javascript"></script>
<!-- end of page level js -->
</body>

</html>
