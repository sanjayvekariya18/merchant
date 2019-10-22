@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
        Form Validations
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/form2.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/form3.css')}}"/>
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Form Validations
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-home"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="#">Forms</a>
                </li>
                <li class="active">
                    Form Validations
                </li>
            </ol>
        </section>
        <!--section ends-->
        <section class="content">
            <!--main content-->
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-star-half-empty"></i> Basic Form Validation
                            </h3>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <form id="form-validation" action="#"
                                  class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="val-username">
                                        Username
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" id="val-username" name="firstName" class="form-control"
                                               placeholder="Enter your name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="email">
                                        Email
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" id="email" name="email" class="form-control"
                                               placeholder="Enter your valid email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="password">
                                        Password
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="password" id="password" name="password" class="form-control"
                                               placeholder="Enter your password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="confirmpassword">
                                        Confirm Password
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="password" id="confirmpassword" name="confirmpassword"
                                               class="form-control" placeholder="Confirm password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="message">
                                        Message
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <textarea id="message" name="message" rows="7"
                                                  class="form-control resize_vertical"
                                                  placeholder="Enter your message"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="skill">
                                        Best Skill
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <select id="skill" name="skill" class="form-control">
                                            <option value="">
                                                Please select
                                            </option>
                                            <option value="html">HTML</option>
                                            <option value="css">CSS</option>
                                            <option value="javascript">Javascript</option>
                                            <option value="php">PHP</option>
                                            <option value="mysql">MySQL</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="url">
                                        Website
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" id="url" name="url" class="form-control"
                                               placeholder="http://example.com">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="number">
                                        Phone Number
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" id="number" name="number" class="form-control"
                                               placeholder="Enter your phone number">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Gender
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <div>
                                            <label>
                                                <input class="custom_radio" type="radio" name="gender" value="male"/>
                                                Male
                                            </label>
                                        </div>
                                        <div>
                                            <label>
                                                <input class="custom_radio" type="radio" name="gender" value="female"/>
                                                Female
                                            </label>
                                        </div>
                                        <div>
                                            <label>
                                                <input class="custom_radio" type="radio" name="gender" value="other"/>
                                                Other
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">
                                        <label class="padding7" for="terms">
                                            <input type="checkbox" class="custom_icheck" id="terms" name="terms"
                                                   value="1">&nbsp;&nbsp;I agree to
                                            <a href="#modal-terms" data-toggle="modal">
                                                Terms &amp; Conditions
                                            </a>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group form-actions">
                                    <div class="col-md-8 col-md-offset-4">
                                        <button type="submit" class="btn btn-effect-ripple btn-primary">Submit</button>
                                        <button type="reset" class="btn btn-effect-ripple btn-default reset_btn">Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div id="modal-terms" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title text-center">
                                                <strong>
                                                    Terms and Conditions
                                                </strong>
                                            </h3>
                                        </div>
                                        <div class="modal-body">
                                            <h4 class="page-header">
                                                1.
                                                <strong>General</strong>
                                            </h4>
                                            <p>
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas
                                                ultrices, justo vel imperdiet gravida, urna ligula hendrerit nibh, ac
                                                cursus nibh sapien in purus. Mauris tincidunt tincidunt turpis in porta.
                                                Integer fermentum tincidunt auctor.
                                            </p>
                                            <h4 class="page-header">
                                                2.
                                                <strong>Account</strong>
                                            </h4>
                                            <p>
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas
                                                ultrices, justo vel imperdiet gravida, urna ligula hendrerit nibh, ac
                                                cursus nibh sapien in purus. Mauris tincidunt tincidunt turpis in porta.
                                                Integer fermentum tincidunt auctor.
                                            </p>
                                            <h4 class="page-header">
                                                3.
                                                <strong>Service</strong>
                                            </h4>
                                            <p>
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas
                                                ultrices, justo vel imperdiet gravida, urna ligula hendrerit nibh, ac
                                                cursus nibh sapien in purus. Mauris tincidunt tincidunt turpis in porta.
                                                Integer fermentum tincidunt auctor.
                                            </p>
                                            <h4 class="page-header">
                                                4.
                                                <strong>Payments</strong>
                                            </h4>
                                            <p>
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas
                                                ultrices, justo vel imperdiet gravida, urna ligula hendrerit nibh, ac
                                                cursus nibh sapien in purus. Mauris tincidunt tincidunt turpis in porta.
                                                Integer fermentum tincidunt auctor.
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="text-center">
                                                <button type="button" class="btn btn-effect-ripple btn-primary"
                                                        data-dismiss="modal">
                                                    I've read them!
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-pencil-square-o"></i> Registration Form Validation
                            </h3>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <form method="post">
                                <div class="form-group">
                                    <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert"
                                                aria-hidden="true">&times;</button>
                                        Validation will be completed on Tab or Enter key press.
                                    </div>
                                    <label for="validate-text">
                                        Validate Text
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="validate-text" id="validate-text"
                                               placeholder="Enter Validate Text" required>
                                        <span class="input-group-addon danger">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="validate-optional">Optional</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="validate-optional"
                                               id="validate-optional" placeholder="Optional">
                                        <span class="input-group-addon info">
                                                <span class="glyphicon glyphicon-asterisk"></span>
                                            </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="validate-email">
                                        Validate Email
                                    </label>
                                    <div class="input-group" data-validate="email">
                                        <input type="text" class="form-control" name="validate-email"
                                               id="validate-email" placeholder="Enter Validate Email" required>
                                        <span class="input-group-addon danger">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="validate-phone">
                                        Validate Phone
                                    </label>
                                    <div class="input-group" data-validate="phone">
                                        <input type="text" class="form-control" name="validate-phone"
                                               id="validate-phone" placeholder="(814) 555-1234" required>
                                        <span class="input-group-addon danger">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="validate-length">
                                        Minimum length 5 characters
                                    </label>
                                    <div class="input-group" data-validate="length" data-length="5">
                                        <textarea class="form-control resize_vertical" name="validate-length"
                                                  id="validate-length" placeholder="Minimum length 5 characters"
                                                  required></textarea>
                                        <span class="input-group-addon danger">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="validate-select">
                                        Validate Select
                                    </label>
                                    <div class="input-group">
                                        <select class="form-control" name="validate-select" id="validate-select"
                                                required>
                                            <option value="">
                                                Select an item
                                            </option>
                                            <option value="item_1">Item 1</option>
                                            <option value="item_2">Item 2</option>
                                            <option value="item_3">Item 3</option>
                                        </select>
                                        <span class="input-group-addon danger">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="validate-number">
                                        Validate Number
                                    </label>
                                    <div class="input-group" data-validate="number">
                                        <input type="text" class="form-control" name="validate-number"
                                               id="validate-number" placeholder="Validate Number" required>
                                        <span class="input-group-addon danger">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-key"></i> Password Validation
                            </h3>
                            <span class="pull-right">
                                        <i class="fa fa-fw fa-chevron-up clickable"></i>
                                     <i class="fa fa-fw fa-times removepanel clickable"></i>
                                    </span>
                        </div>
                        <div class="panel-body">
                            <form action="#" method="post" id="passwordForm">
                                <!-- CSRF Token -->
                                <input type="hidden" name="_token" value="KgCjdhwc9sMwnjx2SkiKDa9rfCkQ8KrPVpnXg7yY"/>
                                <input type="password" class="input-md form-control" name="password1" id="password1"
                                       placeholder="New Password" autocomplete="off">
                                <div>
                                    <div class="col-sm-6 padding">
                                        <span id="8char" class="glyphicon glyphicon-remove"
                                              style="color:#FE5B5B;"></span> 8 Characters Long
                                        <br>
                                        <span id="ucase" class="glyphicon glyphicon-remove"
                                              style="color:#FE5B5B;"></span> One Uppercase Letter
                                    </div>
                                    <div class="col-sm-6 padding">
                                        <span id="lcase" class="glyphicon glyphicon-remove"
                                              style="color:#FE5B5B;"></span> One Lowercase Letter
                                        <br>
                                        <span id="num" class="glyphicon glyphicon-remove" style="color:#FE5B5B;"></span>
                                        One Number
                                    </div>
                                </div>
                                <input type="password" class="input-md form-control" name="password2" id="password2"
                                       placeholder="Repeat Password" autocomplete="off">
                                <div>
                                    <div class="col-sm-12 padding">
                                        <span id="pwmatch" class="glyphicon glyphicon-ok" style="color:#2ECC71;"></span>
                                        Passwords Match
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <a href="#" class="col-xs-12 btn btn-primary btn-load btn-md"
                                       data-loading-text="Changing Password...">Change Password</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-warning">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <i class="fa fa-fw fa-folder"></i> Validations In Modal
                                    </h3>
                                    <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>

                                </span>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <button type="button" class="btn btn-primary btn-lg center-block"
                                                data-toggle="modal" data-target="#myModal">
                                            Click to open form in modal
                                        </button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
                                             aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-hidden="true">×
                                                        </button>
                                                        <h4 class="modal-title" id="myModalLabel">Form Modal</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form  id="form-validation3" action="#">
                                                            <div class="row">
                                                                <div class="col-xs-12 col-sm-6 col-md-6">
                                                                    <div class="form-group">
                                                                        <input type="text" name="modalfirst_name"
                                                                               id="modalfirst_name"
                                                                               class="form-control input-md"
                                                                               placeholder="First Name" tabindex="1"
                                                                               data-error="First name must be entered"
                                                                               required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 col-sm-6 col-md-6">
                                                                    <div class="form-group">
                                                                        <input type="text" name="modallast_name"
                                                                               id="modallast_name"
                                                                               class="form-control input-md"
                                                                               placeholder="Last Name" tabindex="2"
                                                                               data-error="Last name must be entered"
                                                                               required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <input type="text" name="display_name"
                                                                               id="display_name"
                                                                               class="form-control input-md"
                                                                               placeholder="Display Name" tabindex="3"
                                                                               data-error="Username must be enetered"
                                                                               required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <input type="text" name="email"
                                                                               class="form-control input-md"
                                                                               placeholder="Email Address" tabindex="4"
                                                                               data-error="that email address is invalid"
                                                                               required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-xs-12 col-sm-6 col-md-6">
                                                                    <div class="form-group">
                                                                        <input type="password" name="modalpassword"
                                                                               id="modalpassword"
                                                                               class="form-control input-md"
                                                                               placeholder="Password" tabindex="5"
                                                                               required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 col-sm-6 col-md-6">
                                                                    <div class="form-group">
                                                                        <input type="password" name="confirmpassword"
                                                                               class="form-control input-md"
                                                                               placeholder="Confirm Password"
                                                                               data-match="#password"
                                                                               data-match-error="conform passwork should be same as password"
                                                                               tabindex="6" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <div class="col-md-8">
                                                                            <label class="padding7" for="modalterms">
                                                                                <input type="checkbox"
                                                                                       class="custom_icheck"
                                                                                       id="modalterms" name="modalterms"
                                                                                       value="1">&nbsp;&nbsp;I agree
                                                                            </label>
                                                                        </div>
                                                                        <div class="help-block with-errors"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <p class="margin-top">
                                                                        By clicking on the
                                                                        <strong class="label label-primary">Register</strong>
                                                                        , you agree the following
                                                                        <a href="#">Terms and Conditions</a> liability
                                                                        as set out in this site, including our Cookie
                                                                        Use.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="row marginTop">
                                                                <div class="col-xs-6 col-md-6">
                                                                    <input type="submit" id="btncheck" value="Register"
                                                                           class="btn btn-primary btn-block btn-md btn-responsive"
                                                                           tabindex="7">
                                                                </div>
                                                                <div class="col-xs-6 col-md-6">
                                                                    <a class="btn btn-success btn-block btn-md btn-responsive">Sign
                                                                        In</a>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        <!-- /.modal -->
                                    </div>
                                </div>
                            </div>
                            <!--image upload -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">
                                                <i class="fa fa-fw fa-pencil"></i> Form Validations
                                            </h3>
                                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                                        </div>
                                        <div class="panel-body">
                                            <form action="#" name="frmOnline" onsubmit="return Validation()"
                                                  enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <input type="text" name="txtName" id="txtName"
                                                                   class="form-control input-md"
                                                                   placeholder="First Name"></div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <input type="text" name="last_name" id="txtlastname"
                                                                   class="form-control input-md"
                                                                   placeholder="Last Name"></div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <input type="text" name="txtEmail" id="txtEmail"
                                                                   class="form-control input-md"
                                                                   placeholder="Enter Your Email Id"></div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <input type="text" name="display_name" id="txtAddress1"
                                                                   class="form-control input-md"
                                                                   placeholder="Address Line1"></div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <input type="text" name="display_name" id="txtAddress2"
                                                                   class="form-control input-md"
                                                                   placeholder="Address Line2"></div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">

                                                            <select id="e1" class="form-control select2">
                                                                <option value="">
                                                                    Select State
                                                                </option>
                                                                <optgroup label="Alaskan/Hawaiian Time Zone">
                                                                    <option value="AK">Alaska</option>
                                                                    <option value="HI">Hawaii</option>
                                                                </optgroup>
                                                                <optgroup label="Pacific Time Zone">
                                                                    <option value="CA">California</option>
                                                                    <option value="NV">Nevada</option>
                                                                    <option value="OR">Oregon</option>
                                                                    <option value="WA">Washington</option>
                                                                </optgroup>
                                                                <optgroup label="Mountain Time Zone">
                                                                    <option value="AZ">Arizona</option>
                                                                    <option value="CO">Colorado</option>
                                                                    <option value="ID">Idaho</option>
                                                                    <option value="MT">Montana</option>
                                                                    <option value="NE">Nebraska</option>
                                                                    <option value="NM">New Mexico</option>
                                                                    <option value="ND">
                                                                        North Dakota
                                                                    </option>
                                                                    <option value="UT">Utah</option>
                                                                    <option value="WY">Wyoming</option>
                                                                </optgroup>
                                                                <optgroup label="Central Time Zone">
                                                                    <option value="AL">Alabama</option>
                                                                    <option value="AR">Arkansas</option>
                                                                    <option value="IL">Illinois</option>
                                                                    <option value="IA">Iowa</option>
                                                                    <option value="KS">Kansas</option>
                                                                    <option value="KY">Kentucky</option>
                                                                    <option value="LA">Louisiana</option>
                                                                    <option value="MN">Minnesota</option>
                                                                    <option value="MS">
                                                                        Mississippi
                                                                    </option>
                                                                    <option value="MO">Missouri</option>
                                                                    <option value="OK">Oklahoma</option>
                                                                    <option value="SD">
                                                                        South Dakota
                                                                    </option>
                                                                    <option value="TX">Texas</option>
                                                                    <option value="TN">Tennessee</option>
                                                                    <option value="WI">Wisconsin</option>
                                                                </optgroup>
                                                                <optgroup label="Eastern Time Zone">
                                                                    <option value="CT">
                                                                        Connecticut
                                                                    </option>
                                                                    <option value="DE">Delaware</option>
                                                                    <option value="FL">Florida</option>
                                                                    <option value="GA">Georgia</option>
                                                                    <option value="IN">Indiana</option>
                                                                    <option value="ME">Maine</option>
                                                                    <option value="MD">Maryland</option>
                                                                    <option value="MA">
                                                                        Massachusetts
                                                                    </option>
                                                                    <option value="MI">Michigan</option>
                                                                    <option value="NH">
                                                                        New Hampshire
                                                                    </option>
                                                                    <option value="NJ">New Jersey</option>
                                                                    <option value="NY">New York</option>
                                                                    <option value="NC">
                                                                        North Carolina
                                                                    </option>
                                                                    <option value="OH">Ohio</option>
                                                                    <option value="PA">
                                                                        Pennsylvania
                                                                    </option>
                                                                    <option value="RI">
                                                                        Rhode Island
                                                                    </option>
                                                                    <option value="SC">
                                                                        South Carolina
                                                                    </option>
                                                                    <option value="VT">Vermont</option>
                                                                    <option value="VA">Virginia</option>
                                                                    <option value="WV">
                                                                        West Virginia
                                                                    </option>
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <input type="text" name="display_name" id="txtPhone"
                                                                   class="form-control input-md"
                                                                   placeholder="Phone Number"></div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="col-xs-6 col-md-4 col-md-offset-4">
                                                            <input type="submit" id="btncheck1" value="Register"
                                                                   class="btn btn-primary btn-block btn-md btn-responsive"
                                                                   tabindex="7">
                                                        </div>

                                                        <div class="col-xs-6 col-md-4">
                                                            <input type="reset" value="Cancel"
                                                                   class="btn btn-success btn-block btn-md btn-responsive">
                                                        </div>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">
                                                <i class="fa fa-fw fa-keyboard-o"></i> Bootstrap Input MaxLength
                                            </h3>
                                            <span class="pull-right">
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                                        </div>
                                        <div class="panel-body">
                                            <!--max length starts-->
                                            <div class="form-group">
                                                <label for="placement" class="control-label">
                                                    Custom Position
                                                </label>
                                                <input type="text" class="form-control" maxlength="25" name="placement"
                                                       id="placement"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="moreoptions" class="control-label">Options</label>
                                                <input type="text" class="form-control" maxlength="25"
                                                       name="moreoptions" id="moreoptions"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="alloptions" class="control-label">
                                                    All the options
                                                </label>
                                                <input type="text" class="form-control" maxlength="25" name="alloptions"
                                                       id="alloptions"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="textarea" class="control-label">Text Area</label>
                                                <textarea id="textarea" class="form-control resize_vertical"
                                                          maxlength="225" rows="4"
                                                          placeholder="This text area has a limit of 225 chars."></textarea>
                                            </div>
                                            <!--min length ends-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- col-md-6 -->
                        <!--row ends-->
                        @include('layouts.right_sidebar')
                    </div>
                </div>
            </div>
            <!-- row -->

        <!-- right side bar end -->
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-maxlength/js/bootstrap-maxlength.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/form1.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/form2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/form3.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/form_validations.js')}}"></script>
    <!-- end of page level js -->
@stop
