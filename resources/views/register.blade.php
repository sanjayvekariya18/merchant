<!DOCTYPE html>
<html>

<head>
    <title>::Admin Register::</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}" />
    <!-- global css -->
    <link href="{{asset('assets/css/app.css')}}" rel="stylesheet">
    <!-- end of global css -->
    <!--page level css -->
    <link rel="stylesheet" href="{{asset('assets/vendors/iCheck/css/all.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link href="{{asset('assets/css/register2.css')}}" rel="stylesheet">
    <!--end of page level css-->
</head>

<body class="bg-slider">
<div class="preloader">
    <div class="loader_img"><img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64"></div>
</div>
<div class="container">
    <div class="row " id="form-login">

        <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 register-content">
            <div class="row">
               <div class="col-md-12">
                   <div class="header">
                       <h2 class="text-center">
                           Sign Up
                           <small> with</small>
                           <img src="{{asset('assets/img/pages/logo.png')}}" alt="logo">
                       </h2>
                   </div>
               </div>
            </div>
            <div class="row row-bg-color">
                <div class="col-md-8 core-register">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                        {{ csrf_field() }}                    
                        <!-- CSRF Token -->
                        <!-- <input type="hidden" name="_token" value="sSAo7cToGJCJ2IBFgOpYbLNnqV5n8O4DdNG5jdez"/> -->
                        <!-- <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label" for="user_name">USER NAME</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="User Name"
                                               name="user_name" id="user_name" value=""/>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="row form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group ">
                                    <label class="control-label" for="email">EMAIL</label>
                                    <div class="input-group">
                                        <input type="text" placeholder="Email Address" class="form-control" name="email"
                                               id="email" value=""/>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="row form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row password">
                            <!-- <div class="col-sm-6">
                                <div class="form-group ">
                                    <label class="control-label" for="password">PASSWORD</label>
                                    <div class="input-group">
                                        <input type="password" placeholder="Password" class="form-control"
                                               name="password" id="password"/>
                                    </div>
                                </div>
                            </div> -->
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <!-- <div class="col-sm-6">
                                <div class="form-group cp-group">
                                    <label class="control-label confirm_pwd" for="password_confirm">CONFIRM PASSWORD</label>
                                    <div class="input-group pull-right">
                                        <input type="password" placeholder="Confirm Password" class="form-control"
                                               name="password_confirm" id="password_confirm"/>
                                    </div>
                                </div>
                            </div> -->
                            <div class="form-group">
                                <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                                <label class="checkbox-inline sr-only" for="terms">Agree to terms and conditions</label>
                                <input type="checkbox" value="1" name="terms" id="terms"/>&nbsp;
                                <label for="terms"> I agree to <a href="#section"> Terms and Conditions</a>.</label>
                        </div>
                        <div class="form-group ">
                                <button type="submit" class="btn btn-primary" >Sign Up</button>
                                <input type="reset" class="btn btn-default" value="Reset" id="dee1"/><br>
                                <hr>
                                <span> Already Have an account? <a href="{{URL::to('login')}}">Login</a></span>
                        </div>
                    </form>
                </div>
                <div class="col-md-4">
                    <div class="social-buttons">
                        <p class="text-center"><label>YOU CAN ALSO SIGN UP WITH</label></p>
                        <a class="btn btn-block btn-social btn-google-plus">
                            <i class="fa fa-google-plus"></i>
                            Sign Up with Google
                        </a>
                        <a class="btn btn-block btn-social btn-facebook">
                            <i class="fa fa-facebook"></i>
                            Sign Up with Facebook
                        </a>
                        <a class="btn btn-block btn-social btn-twitter">
                            <i class="fa fa-twitter"></i>
                            Sign Up with Twitter
                        </a>
                        <a class="btn btn-block btn-social btn-linkedin">
                            <i class="fa fa-linkedin"></i>
                            Sign Up with LinkedIn
                        </a>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- global js -->
<script src="{{asset('assets/js/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/bootstrap.min.js')}}" type="text/javascript"></script>
<!-- end of global js -->
<!-- begining of page level js -->
<script src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/backstretch.js')}}"></script>
<script src="{{asset('assets/js/custom_js/register.js')}}"></script>
<!-- end of page level js -->
</body>

</html>
