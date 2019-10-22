<!DOCTYPE html>
<html>

<head>
    <title>::Admin Login::</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <!-- Bootstrap -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- end of bootstrap -->
    <!--page level css -->
    <link type="text/css" href="{{asset('assets/css/font-awesome.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/vendors/iCheck/css/all.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/css/login.css')}}" rel="stylesheet">
    <!--end page level css-->
</head>

<body>
<div class="preloader">
    <div class="loader_img"><img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64"></div>
</div>
<div class="container">
    <div class="row">
        <div class="panel-header">
            <h2 class="text-center">
                Log In or
                <a href="register2">Sign Up</a>
            </h2>
        </div>
        <div class="panel-body social col-sm-offset-2">
            <div class="col-xs-4 col-sm-3">
                <a href="#" class="btn btn-lg btn-block btn-facebook"> <i class="fa fa-facebook-square fa-lg"></i>
                    <span class="hidden-sm hidden-xs">Facebook</span>
                </a>
            </div>
            <div class="col-xs-4 col-sm-3">
                <a href="#" class="btn btn-lg btn-block btn-twitter"> <i class="fa fa-twitter-square fa-lg"></i>
                    <span class="hidden-sm hidden-xs">Twitter</span>
                </a>
            </div>
            <div class="col-xs-4 col-sm-3">
                <a href="#" class="btn btn-lg btn-block btn-google">
                    <i class="fa fa-google-plus-square fa-lg"></i>
                    <span class="hidden-sm hidden-xs">Google+</span>
                </a>
            </div>
            <div class="clearfix">
                <div class="col-xs-12 col-sm-9">
                    <hr class="omb_hrOr">
                    <span class="omb_spanOr">or</span>
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-12 col-sm-6 form_width">
                    <form action="{{URL::to('index')}}" id="authentication" class="login_validator">
                        <div class="form-group">
                            <label for="email" class="sr-only"> E-mail</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-envelope text-primary"></i></span>
                                <input type="text" class="form-control  form-control-lg" id="email" name="username"
                                       placeholder="E-mail">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="sr-only">Password</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-lock text-primary"></i></span>
                                <input type="password" class="form-control form-control-lg" id="password"
                                       name="password" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group checkbox">
                            <label for="remember">
                                <input type="checkbox" name="remember" id="remember"> Remember Me
                            </label>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Log In</button>
                        </div>
                        <a href="{{URL::to('forgot_password')}}" id="forgot" class="forgot"> Forgot Password? </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- global js -->
<script src="{{asset('assets/js/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/bootstrap.min.js')}}" type="text/javascript"></script>
<!-- end of global js -->
<!-- page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/login2.js')}}"></script>
<!-- end of page level js -->
</body>

</html>
