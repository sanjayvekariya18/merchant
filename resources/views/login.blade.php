<!DOCTYPE html>
<html>

<head>
    <title>::Admin Portal Login::</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}" />
    <!-- Bootstrap -->
    <!-- global css -->
    <link href="{{asset('assets/css/app.css')}}" rel="stylesheet">
    <!-- end of global css -->
    <!--page level css -->
    <link rel="stylesheet" href="{{asset('assets/vendors/iCheck/css/all.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link href="{{asset('assets/css/login2.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendors/toastr/css/toastr.min.css')}}" rel="stylesheet"/>
    <!--end page level css-->
</head>

<body class="bg-slider">
<div class="preloader">
    <div class="loader_img"><img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64"></div>
</div>
<script type="text/javascript">
    var currentTime = new Date();
    var firstMonthTime = new Date(currentTime.getFullYear(), 0, 1, 0, 0, 0, 0);
    var gmtTempTime = firstMonthTime.toGMTString();
    var secondMonthTime = new Date(gmtTempTime.substring(0, gmtTempTime.lastIndexOf(" ")-1));
    var standardTimeOffset = (firstMonthTime - secondMonthTime) / (1000 * 60 * 60);
    var firstGmtMothTime = new Date(currentTime.getFullYear(), 6, 1, 0, 0, 0, 0);
    gmtTempTime = firstGmtMothTime.toGMTString();
    var secondGmtMothTime = new Date(gmtTempTime.substring(0, gmtTempTime.lastIndexOf(" ")-1));
    var dayLightTimeOffset = (firstGmtMothTime - secondGmtMothTime) / (1000 * 60 * 60);
    var dayLightSavingTime;
    if (standardTimeOffset == dayLightTimeOffset) {
        dayLightSavingTime = 0; // daylight savings time is NOT observed
    } else {
        dayLightSavingTime = 1; // daylight savings time is observed
    }
    var timeZoneOffset = dayLightTimeOffset-dayLightSavingTime;
    document.cookie = "timeZoneOffset="+timeZoneOffset;
</script>
<?php 
use App\Http\Traits\PermissionTrait; 
?>
<div class="container">
    <div class="row " id="form-login">
        <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 login-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="header">
                        <h2 class="text-center">
                            Login
                            <small> with</small>
                            Admin Portal
                        </h2>
                    </div>
                </div>
            </div>
            <div class="row row-bg-color" style="background: #ffffff">
                <div class="col-md-6 core-login">
                    <form class="form-horizontal" role="form" action="{{ url('/login') }}" method="POST" id="authentication">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group ">
                                    <label class="control-label" for="email">USERNAME</label>
                                    <div class="input-group">
                                        <input id="username" type="username" class="form-control" name="username" value="{{ old('username') }}" required autofocus>

                                        @if ($errors->has('username'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('username') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group ">
                                    <label class="control-label" for="password">PASSWORD</label>
                                    <div class="input-group">
                                        <input id="password" type="password" class="form-control" name="password" required>

                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <button type="submit"  class="btn btn-primary login-btn">Login</button>
                            <br>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="social-buttons">
                        <p class="text-center">
                            <label>YOU CAN ALSO LOGIN WITH</label>
                        </p>
                        <?php if(PermissionTrait::getSocialConnectorStatus("Google")){ ?>
                        <a class="btn btn-block btn-social btn-google-plus" href="{{ url('/google/login') }}">
                            <i class="fa fa-google-plus"></i> Login with Google
                        </a>
                        <?php } if(PermissionTrait::getSocialConnectorStatus("Facebook")){ ?>
                        <a class="btn btn-block btn-social btn-facebook" href="{{ url('/facebook/login') }}">
                            <i class="fa fa-facebook"></i> Login with Facebook
                        </a>
                        <?php } if(PermissionTrait::getSocialConnectorStatus("Twitter")){ ?>
                        <a class="btn btn-block btn-social btn-twitter" href="{{ url('/twitter/login') }}">
                            <i class="fa fa-twitter"></i> Login with Twitter
                        </a>
                        <?php } if(PermissionTrait::getSocialConnectorStatus("Linkedin")){ ?>
                        <a class="btn btn-block btn-social btn-linkedin" href="{{ url('/linkedin/login') }}">
                            <i class="fa fa-linkedin"></i> Login with LinkedIn
                        </a>
                        <?php } if(PermissionTrait::getSocialConnectorStatus("Github")){ ?>
                        <a class="btn btn-block btn-social btn-github" href="{{ url('/github/login') }}">
                            <i class="fa fa-github"></i> Login with GitHub
                        </a>
                        <?php } if(PermissionTrait::getSocialConnectorStatus("Meetup")){ ?>
                        <a class="btn btn-block btn-social btn-meetup" href="{{ url('/meetup/login') }}">
                            <i class="fa fa-meetup"></i> Login with Meetup
                        </a>
                        <?php } if(PermissionTrait::getSocialConnectorStatus("Eventbrite")){ ?>
                        <a class="btn btn-block btn-social btn-eventbrite" href="{{ url('/eventbrite/login') }}">
                            <i class="fa fa-etsy"></i> Login with Eventbrite
                        </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- global js -->
<script src="{{asset('assets/js/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/backstretch.js')}}"></script>
<!-- end of global js -->
<!-- page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/login.js')}}"></script>
<script src="https://use.fontawesome.com/499f560beb.js"></script>
<script type="text/javascript" src="{{asset('assets/vendors/toastr/js/toastr.min.js')}}"></script>
<!-- end of page level js -->
<script type="text/javascript">
    
    @if(Session::has('type'))
        toastr.options = {
            "closeButton": true,
            "positionClass": "toast-top-center",
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "swing",
            "showMethod": "show"
        };
        var $toast = toastr["{{ Session::get('type') }}"]("", "{{ Session::get('msg') }}");
    @endif
</script>
</body>

</html>
