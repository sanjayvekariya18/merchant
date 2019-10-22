<!DOCTYPE html>
<html>

<head>
    <title>::Admin Register::</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <!-- global css -->
    <link href="{{asset('assets/css/app.css')}}" rel="stylesheet">
    <!-- end of global css -->
    <!--page level css -->
    <link rel="stylesheet" href="{{asset('assets/vendors/iCheck/css/all.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/vendors/bootstrap-datepicker/css/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/select2/css/select2.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link href="{{asset('assets/css/login.css')}}" rel="stylesheet">
    <!--end of page level css-->
</head>

<body>
<div class="preloader">
    <div class="loader_img"><img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64"></div>
</div>
<div class="container">
    <div class="row " id="form-login">
        <div class="col-sm-12">
            <div class="panel-header">
                <h2 class="text-center text-primary">
                    Sign Up or
                    <a href="{{URL::to('login2')}} ">Log In</a>
                </h2>
            </div>
            <div class="panel-body social">
                <div class="row">
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                    <form class="form-horizontal" action="{{URL::to('login2')}}" id="register_form">
                        <!-- CSRF Token -->
                        <input type="hidden" name="_token" value="sSAo7cToGJCJ2IBFgOpYbLNnqV5n8O4DdNG5jdez"/>
                        <div class="form-group ">
                            <label class="control-label col-sm-3" for="first_name">First Name<sup>*</sup> :</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                        <span class="input-group-addon"> <i class="fa fa-fw fa-user-md text-primary"></i>
                                        </span>
                                    <input type="text" class="form-control" placeholder="First Name" name="first_name"
                                           id="first_name" value=""/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="control-label col-sm-3" for="last_name">Last Name<sup>*</sup>  :</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Last Name" name="last_name"
                                           id="last_name" value=""/>
                                    <span class="input-group-addon"> <i class="fa fa-fw fa-user-md text-primary"></i>
                                        </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="control-label col-sm-3" for="email">Email<sup>*</sup>  :</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-envelope text-primary"></i>
                                        </span>
                                    <input type="text" placeholder="Email Address" class="form-control" name="email"
                                           id="email" value=""/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="control-label col-sm-3" for="email_confirm">Confirm Email<sup>*</sup>  :</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-envelope text-primary"></i>
                                        </span>
                                    <input type="text" placeholder="Confirm Email Address" class="form-control"
                                           name="email_confirm" id="email_confirm" value=""/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="control-label col-sm-3" for="password">Password<sup>*</sup>  :</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-fw fa-key text-primary"></i>
                                        </span>
                                    <input type="password" placeholder="Password" class="form-control" name="password"
                                           id="password"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="control-label col-sm-3" for="password_confirm">Confirm Password<sup>*</sup>  :</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="password" placeholder="Confirm Password" class="form-control"
                                           name="password_confirm" id="password_confirm"/>
                                    <span class="input-group-addon">
                                            <i class="fa fa-fw fa-key text-primary"></i>
                                        </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3" for="phone">Phone<sup>*</sup>  :</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-fw fa-phone text-primary"></i>
                                        </span>
                                    <input type="text" placeholder="Phone Number" class="form-control" name="phone"
                                           id="phone" value=""/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-xs-3">Gender:</label>
                            <div class="col-xs-4 mar-top4">
                                <label class="radio-inline">
                                    <input type="radio" name="gender" class="flat-red" id="radio_d1" value="male"/> Male
                                </label>
                            </div>
                            <div class="col-xs-5 mar-top4">
                                <label class="radio-inline">
                                    <input type="radio" name="gender" class="flat-red " id="radio_d2" value="female"/>
                                    Female
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-offset-3 col-xs-9">
                                <label class="checkbox-inline sr-only" for="terms">Agree to terms and conditions</label>
                                <input type="checkbox" value="1" name="terms" id="terms"/> &nbsp;
                                <label for="terms"> I agree to Terms and Conditions.</label>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="col-xs-offset-3 col-xs-9">
                                <button type="submit" class="btn btn-primary">Register</button>
                                <input type="reset" class="btn btn-default" value="Reset" id="dee1"/>
                            </div>
                        </div>
                    </form>
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
<script src="{{asset('assets/vendors/moment/js/moment.min.js')}}"></script>
<script src="{{asset('assets/vendors/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/register2.js')}}"></script>
<!-- end of page level js -->
</body>

</html>
