@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Add New User
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css')}}" >
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/wizard.css')}}">
    <!--end of page level css -->
@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <!--section starts-->
        <h1>
            Add New User
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="index">
                    <i class="fa fa-fw fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="#"> Users</a>
            </li>
            <li class="active">
                Add New User
            </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff"
                               data-loop="true"></i> Add New User
                        </h3>
                        <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                </span>
                    </div>
                    <div class="panel-body">
                        <!-- errors -->
                        <!--main content-->
                        <form id="adduser_form" method="POST" enctype="multipart/form-data" class="form-horizontal">
                            <!-- CSRF Token -->
                            <input type="hidden" name="_token"/>
                            <div id="pager_wizard">
                                <ul>
                                    <li>
                                        <a href="#tab1" data-toggle="tab">User Profile</a>
                                    </li>
                                    <li>
                                        <a href="#tab2" data-toggle="tab">Bio</a>
                                    </li>
                                    <li>
                                        <a href="#tab3" data-toggle="tab">Address</a>
                                    </li>
                                    <li>
                                        <a href="#tab4" data-toggle="tab">User Group</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane" id="tab1">
                                        <h2 class="hidden">&nbsp;</h2>
                                        <div class="form-group">
                                            <label for="first_name" class="col-sm-3 control-label">First Name *
                                            </label>
                                            <div class="col-sm-9">
                                                <input id="first_name" name="first_name" type="text"
                                                       placeholder="First Name" class="form-control required"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="last_name" class="col-sm-3 control-label">Last Name *
                                            </label>
                                            <div class="col-sm-9">
                                                <input id="last_name" name="last_name" type="text"
                                                       placeholder="Last Name" class="form-control required"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="email" class="  col-sm-3 control-label">Email *</label>
                                            <div class="col-sm-9">
                                                <input id="email" name="email" placeholder="E-mail" type="text"
                                                       class="form-control required email"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="password" class="  col-sm-3 control-label">Password
                                                *</label>
                                            <div class="col-sm-9">
                                                <input id="password" name="password" type="password"
                                                       placeholder="Password" class="form-control required"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="password_confirm" class="  col-sm-3 control-label">Confirm
                                                Password *
                                            </label>
                                            <div class="col-sm-9">
                                                <input id="password_confirm" name="password_confirm" type="password"
                                                       placeholder="Confirm Password "
                                                       class="form-control required"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab2">
                                        <h2 class="hidden">&nbsp;</h2>
                                        <div class="form-group required">
                                            <label for="dob" class="  col-sm-3 control-label">Date of Birth</label>
                                            <div class="col-sm-9">
                                                <input id="dob" name="dob" type="text" class="form-control"
                                                       placeholder="dd/mm/yyyy"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="pic" class="  col-sm-3 control-label">Profile
                                                picture</label>
                                            <div class="col-sm-9">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail"
                                                         style="width: 200px; height: 200px;">
                                                        <img src="http://placehold.it/200x200" alt="profile pic">
                                                    </div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail"
                                                         style="max-width: 200px; max-height: 200px;"></div>
                                                    <div>
                                                                <span class="btn btn-default btn-file">
                                    <span class="fileinput-new">Select image</span>
                                                                <span class="fileinput-exists">Change</span>
                                                                <input id="pic" name="pic_file" type="file"
                                                                       class="form-control"/>
                                                                </span>
                                                        <a href="#" class="btn btn-danger fileinput-exists"
                                                           data-dismiss="fileinput">Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group required">
                                            <label for="bio" class="  col-sm-3 control-label">Bio
                                                <small>(brief intro) *</small>
                                            </label>
                                            <div class="col-sm-9">
                                                    <textarea name="bio" id="bio" class="form-control resize_vertical"
                                                              rows="4"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab3">
                                        <div class="form-group">
                                            <label for="gender" class="  col-sm-3 control-label">Gender *</label>
                                            <div class="col-sm-9">
                                                <select class="form-control select21" id="gender"
                                                        title="Select Gender..." name="gender">
                                                    <option disabled selected>Select Gender</option>
                                                    <option value="male">MALE</option>
                                                    <option value="female">FEMALE</option>
                                                    <option value="other">OTHER</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="country" class="  col-sm-3 control-label">Country</label>
                                            <div class="col-sm-9">
                                                <select class="select21 form-control" name="country" id="country">
                                                    <optgroup label="Alaskan/Hawaiian Time Zone">
                                                        <option>Select Country</option>
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
                                                        <option value="ND">North Dakota</option>
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
                                                        <option value="MS">Mississippi</option>
                                                        <option value="MO">Missouri</option>
                                                        <option value="OK">Oklahoma</option>
                                                        <option value="SD">South Dakota</option>
                                                        <option value="TX">Texas</option>
                                                        <option value="TN">Tennessee</option>
                                                        <option value="WI">Wisconsin</option>
                                                    </optgroup>
                                                    <optgroup label="Eastern Time Zone">
                                                        <option value="CT">Connecticut</option>
                                                        <option value="DE">Delaware</option>
                                                        <option value="FL">Florida</option>
                                                        <option value="GA">Georgia</option>
                                                        <option value="IN">Indiana</option>
                                                        <option value="ME">Maine</option>
                                                        <option value="MD">Maryland</option>
                                                        <option value="MA">Massachusetts</option>
                                                        <option value="MI">Michigan</option>
                                                        <option value="NH">New Hampshire</option>
                                                        <option value="NJ">New Jersey</option>
                                                        <option value="NY">New York</option>
                                                        <option value="NC">North Carolina</option>
                                                        <option value="OH">Ohio</option>
                                                        <option value="PA">Pennsylvania</option>
                                                        <option value="RI">Rhode Island</option>
                                                        <option value="SC">South Carolina</option>
                                                        <option value="VT">Vermont</option>
                                                        <option value="VA">Virginia</option>
                                                        <option value="WV">West Virginia</option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="state" class="  col-sm-3 control-label">State </label>
                                            <div class="col-sm-9">
                                                <input id="state" name="state" type="text" class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="form-group required">
                                            <label for="city" class="  col-sm-3 control-label">City </label>
                                            <div class="col-sm-9">
                                                <input id="city" name="city" type="text" class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="form-group required">
                                            <label for="address" class="  col-sm-3 control-label">Address </label>
                                            <div class="col-sm-9">
                                                <input id="address" name="address" type="text"
                                                       class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="form-group required">
                                            <label for="postal" class="  col-sm-3 control-label">Postal/zip</label>
                                            <div class="col-sm-9">
                                                <input id="postal" name="postal" type="text" class="form-control"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab4">
                                        <p class="text-danger"><strong>Be careful with group selection, if you give
                                                admin access.. they can access admin section</strong></p>
                                        <div class="form-group required">
                                            <label for="group" class="  col-sm-3 control-label">Group *</label>
                                            <div class="col-sm-9">
                                                <select class="form-control required select21"
                                                        title="Select group..." name="group" id="group">
                                                    <option disabled selected>Select Group</option>
                                                    <option value="admin">admin</option>
                                                    <option value="user">user</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="activate" class="  col-sm-3 control-label"> Activate User
                                            </label>
                                            <div class="col-sm-9">
                                                <label for="activate">
                                                    <input id="activate" name="activate" type="checkbox"
                                                           class="pos-rel p-l-30 custom-checkbox" value="1"> &nbsp;Activate
                                                    the user otherwise the user won't be able to access their
                                                    account.</label>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="pager wizard">
                                        <li class="previous">
                                            <a href="#">Previous</a>
                                        </li>
                                        <li class="next">
                                            <a href="#">Next</a>
                                        </li>
                                        <li class="next finish" style="display:none;">
                                            <a href="javascript:;">Finish</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div id="myModal" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close"
                                                    data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">User Register</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Account Registered Successfully.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Ok
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--row end-->
        @include('layouts.right_sidebar')
        </section>

@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
    <script type="text/javascript" src="{{asset('assets/vendors/moment/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/vendors/bootstrapwizard/js/jquery.bootstrap.wizard.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/adduser.js')}}"></script>
    <!-- end of page level js -->
@stop
