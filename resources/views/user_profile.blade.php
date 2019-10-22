@extends('layouts/default')

{{-- Page title --}}
@section('title')
    View User
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/simple-line-icons/css/simple-line-icons.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/user_profile.css')}}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                View User
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
                    View User
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="text-center mbl">
                                        <img src="{{asset('assets/img/authors/avatar1.jpg')}}" alt="img" class="img-circle img-bor"/>
                                    </div>
                                </div>
                                <div class="profile_user">
                                    <h3 class="user_name_max">Nataliapery</h3>
                                    <p>nataliaperye@sf.com</p>
                                    <span class="fa-stack faceb fa-lg">
                                         <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-stack-1x fa-facebook fa-inverse"></i>
                                    </span>
                                    <span class="fa-stack googleplus fa-lg">
                                         <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-flag fa-stack-1x fa-google-plus fa-inverse"></i>
                                    </span>
                                    <span class="fa-stack tweet-btn fa-lg">
                                         <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-flag fa-stack-1x fa-twitter fa-inverse"></i>
                                    </span>
                                </div>
                                &nbsp;&nbsp;
                                <div class="profile_user">
                                    <button type="button" class="btn btn-success btn-sm">Follow</button>
                                    <button type="button" class="btn btn-primary btn-sm">Message</button>
                                </div>
                                <br/>
                                <div class="panel panel-widget panel-default">
                                    <div class="panel-heading">
                                        <span class="panel-title"> <i class="icon-chart icons"></i> Project Status<i
                                                class="icon-settings icons pull-right"></i></span>
                                    </div>
                                    <div class="panel-body profile_status">
                                        <div>
                                            <p>
                                                <strong>Admin Template</strong>
                                                <small class="pull-right text-muted">
                                                    40% Complete
                                                </small>
                                            </p>
                                            <div class="progress progress-xs progress-striped active">
                                                <div class="progress-bar progress-bar-success" role="progressbar"
                                                     aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                                     style="width: 40%">
                                                        <span class="sr-only">
                                                    40% Complete (success)
                                                </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <p>
                                                <strong>Wordpress Theme</strong>
                                                <small class="pull-right text-muted">
                                                    60% Complete
                                                </small>
                                            </p>
                                            <div class="progress progress-xs progress-striped active">
                                                <div class="progress-bar progress-bar-warning" role="progressbar"
                                                     aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
                                                     style="width: 60%">
                                                        <span class="sr-only">
                                                    60% Complete (warning)
                                                </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <p>
                                                <strong>Laravel Project</strong>
                                                <small class="pull-right text-muted">
                                                    80% Complete
                                                </small>
                                            </p>
                                            <div class="progress progress-xs progress-striped active">
                                                <div class="progress-bar progress-bar-info" role="progressbar"
                                                     aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"
                                                     style="width: 80%">
                                                        <span class="sr-only">
                                                    80% Complete (info)
                                                </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs nav-custom">
                                            <li class="active">
                                                <a href="#tab-activity" data-toggle="tab">
                                                    <strong>Activities</strong>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#followers" data-toggle="tab">
                                                    <strong>Followers</strong>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#tab-events" data-toggle="tab">
                                                    <strong>My Events</strong>
                                                </a>
                                            </li>
                                        </ul>
                                        <!-- Tab panes -->
                                        <div class="tab-content nopadding noborder">
                                            <div id="tab-activity" class="tab-pane fade in active">
                                                <div class="table-responsive">
                                                    <table class="table table-responsive">
                                                        <tbody>
                                                        <tr>
                                                            <td class="text-center">
                                                                <i class="icon-bubble icons"></i>
                                                            </td>
                                                            <td>
                                                                Jasmine Barlee posted a comment in Avengers Initiative
                                                                project.
                                                            </td>
                                                            <td>
                                                                2016/08/15 10:50
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">
                                                                <i class="icon-rocket icons"></i>
                                                            </td>
                                                            <td>
                                                                Beau Pomeroy changed order status from <span
                                                                    class="label label-primary">Pending</span> to <span
                                                                    class="label label-success">Completed</span>
                                                            </td>
                                                            <td>
                                                                2015/07/19 07:16
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">
                                                                <i class="icon-emotsmile icons"></i>
                                                            </td>
                                                            <td>
                                                                Flynn Toosey posted a comment in Lost in Translation
                                                                opening scene discussion.
                                                            </td>
                                                            <td>
                                                                2014/09/16 10:10
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">
                                                                <i class="icon-people icons"></i>
                                                            </td>
                                                            <td>
                                                                Alex Smithers posted a comment in Avengers Initiative
                                                                project.
                                                            </td>
                                                            <td>
                                                                2015/11/20 05:15
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">
                                                                <i class="icon-heart icons"></i>
                                                            </td>
                                                            <td>
                                                                Ben Drennan changed order status from <span
                                                                    class="label label-warning">On Hold</span> to <span
                                                                    class="label label-danger">Disabled</span>
                                                            </td>
                                                            <td>
                                                                2015/03/13 06:16
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">
                                                                <i class="icon-key icons"></i>
                                                            </td>
                                                            <td>
                                                                Darcy Whitford posted a comment in Lost in Translation
                                                                opening scene discussion.
                                                            </td>
                                                            <td>
                                                                2015/11/10 08:15
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">
                                                                <i class="icon-hourglass icons"></i>
                                                            </td>
                                                            <td>
                                                                Jordan Quinlivan changed order status from <span
                                                                    class="label label-primary">Pending</span> to <span
                                                                    class="label label-success">Completed</span>
                                                            </td>
                                                            <td>
                                                                2014/05/02 10:08
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">
                                                                <i class="icon-info icons"></i>
                                                            </td>
                                                            <td>
                                                                Mary Dobbie posted a comment in Avengers Initiative
                                                                project.
                                                            </td>
                                                            <td>
                                                                2015/09/15 11:08
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">
                                                                <i class="icon-present icons"></i>
                                                            </td>
                                                            <td>
                                                                Lauren Monahan posted a comment in Avengers Initiative
                                                                project.
                                                            </td>
                                                            <td>
                                                                2015/08/08 12:08
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- tab-pane -->
                                            <div class="tab-pane" id="followers">
                                                <div class="row m-t-l-10">
                                                    <div class="col-md-6 col-lg-6 col-sm-6 bord">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="img">
                                                                    <a href="#">
                                                                        <img class="media-object thumbnail img-responsive"
                                                                             height="60" width="60"
                                                                             src="{{asset('assets/img/authors/avatar2.jpg')}}"
                                                                             alt="avatar image">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="details">
                                                                    <div class="name">
                                                                        <a href="#">Scarlett Johansson</a>
                                                                    </div>
                                                                    <div class="time">
                                                                        <i class="icon-clock icons"></i> Last online: 7
                                                                        minutes ago
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-lg-6 col-sm-6 bord">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="img">
                                                                    <a href="#">
                                                                        <img class="media-object thumbnail img-responsive"
                                                                             src="{{asset('assets/img/authors/avatar.jpg')}}"
                                                                             alt="avatar image" height="60" width="60">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="details">
                                                                    <div class="name">
                                                                        <a href="#">Mila Kunis</a>
                                                                    </div>
                                                                    <div class="time online">
                                                                        <i class="icon-check icons"></i> Online
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-lg-6 col-sm-6 bord">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="img">
                                                                    <a href="#">
                                                                        <img class="media-object thumbnail img-responsive"
                                                                             src="{{asset('assets/img/authors/avatar4.jpg')}}"
                                                                             alt="avatar image" height="60" width="60">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="details">
                                                                    <div class="name">
                                                                        <a href="#">Ryan Gossling</a>
                                                                    </div>
                                                                    <div class="time online">
                                                                        <i class="icon-check icons"></i> Online
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-lg-6 col-sm-6 bord">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="img">
                                                                    <a href="#">
                                                                        <img class="media-object thumbnail img-responsive"
                                                                             src="{{asset('assets/img/authors/avatar3.jpg')}}"
                                                                             alt="avatar image" height="60" width="60">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="details">
                                                                    <div class="name">
                                                                        <a href="#">Robert Downey Jr.</a>
                                                                    </div>
                                                                    <div class="time">
                                                                        <i class="icon-clock icons"></i> Last online:
                                                                        Tuesday
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-lg-6 col-sm-6 bord">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="img">
                                                                    <a href="#">
                                                                        <img class="media-object thumbnail img-responsive"
                                                                             src="{{asset('assets/img/authors/avataraa.jpg')}}"
                                                                             alt="avatar image" height="60" width="60">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="details">
                                                                    <div class="name">
                                                                        <a href="#">Emma Watson</a>
                                                                    </div>
                                                                    <div class="time">
                                                                        <i class="icon-clock icons"></i> Last online: 1
                                                                        week ago
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-lg-6 col-sm-6 bord">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="img">
                                                                    <a href="#">
                                                                        <img class="media-object thumbnail img-responsive"
                                                                             src="{{asset('assets/img/authors/avatar7.jpg')}}"
                                                                             alt="avatar image" height="60" width="60">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="details">
                                                                    <div class="name">
                                                                        <a href="#">George Clooney</a>
                                                                    </div>
                                                                    <div class="time">
                                                                        <i class="icon-clock icons"></i> Last online: 1
                                                                        month ago
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-lg-6 col-sm-6 bord">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="img">
                                                                    <a href="#">
                                                                        <img class="media-object thumbnail img-responsive"
                                                                             src="{{asset('assets/img/authors/avatar3.jpg')}}"
                                                                             alt="avatar image" height="60" width="60">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="details">
                                                                    <div class="name">
                                                                        <a href="#">Mila Kunis</a>
                                                                    </div>
                                                                    <div class="time online">
                                                                        <i class="icon-check icons"></i> Online
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-lg-6 col-sm-6 bord">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="img">
                                                                    <a href="#">
                                                                        <img class="media-object thumbnail img-responsive"
                                                                             src="{{asset('assets/img/authors/avatar.jpg')}}"
                                                                             alt="avatar image" height="60" width="60">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="details">
                                                                    <div class="name">
                                                                        <a href="#">Ryan Gossling</a>
                                                                    </div>
                                                                    <div class="time online">
                                                                        <i class="icon-check icons"></i> Online
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab-events">
                                                <div class="events">
                                                    <h5 class="lg-title m-b-15">Upcoming Events</h5>
                                                    <div class="row m-t-l-10">
                                                        <div class="col-sm-12 col-md-12 bord">
                                                            <div class="media">
                                                                <a class="pull-left" href="#">
                                                                    <img class="media-object thumbnail"
                                                                         src="{{asset('assets/img/authors/avatar.jpg')}}" alt="image"
                                                                         height="80" width="80"/>
                                                                </a>
                                                                <div class="media-body">
                                                                    <h4 class="event-title">
                                                                        <a href="#">Lorem Ipsum is simple</a>
                                                                    </h4>
                                                                    <small class="text-muted">
                                                                        <i class="icon-location-pin icons"></i>
                                                                        Silicon Valley, San Francisco, CA
                                                                    </small>
                                                                    <small class="text-muted">
                                                                        <i class="icon-calendar icons"></i>
                                                                        Sunday,Dec 18, 2016 at 11:00am
                                                                    </small>
                                                                    <p>
                                                                        Lorem ipsum dolor sit amet, consectetur
                                                                        adipisicing elit, sed do eiusmod tempor...
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <!-- media -->
                                                        </div>
                                                        <!-- col-sm-6 -->
                                                        <div class="col-sm-12 col-md-12 bord">
                                                            <div class="media">
                                                                <a class="pull-left" href="#">
                                                                    <img class="media-object thumbnail"
                                                                         src="{{asset('assets/img/authors/avatar2.jpg')}}" alt="image"
                                                                         height="80" width="80"/>
                                                                </a>
                                                                <div class="media-body">
                                                                    <h4 class="event-title">
                                                                        <a href="#">Lorem ipsum dolor text</a>
                                                                    </h4>
                                                                    <small class="text-muted">
                                                                        <i class="icon-location-pin icons"></i>
                                                                        Los Angeles, CA
                                                                    </small>
                                                                    <small class="text-muted">
                                                                        <i class="icon-calendar icons"></i>
                                                                        Friday,Dec 20, 2016 at 8:00am
                                                                    </small>
                                                                    <p>
                                                                        Lorem ipsum dolor sit amet, consectetur
                                                                        adipisicing elit, sed do eiusmod tempor...
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <!-- media -->
                                                        </div>
                                                        <div class="col-sm-12 col-md-12 bord">
                                                            <div class="media">
                                                                <a class="pull-left" href="#">
                                                                    <img class="media-object thumbnail"
                                                                         src="{{asset('assets/img/authors/avatar3.jpg')}}" alt="image"
                                                                         height="80" width="80"/>
                                                                </a>
                                                                <div class="media-body">
                                                                    <h4 class="event-title">
                                                                        <a href="#">Lorem Ipsum is not simply random
                                                                            text</a>
                                                                    </h4>
                                                                    <small class="text-muted">
                                                                        <i class="icon-location-pin icons"></i>
                                                                        Los Angeles, CA
                                                                    </small>
                                                                    <small class="text-muted">
                                                                        <i class="icon-calendar icons"></i>
                                                                        Friday,Dec 22, 2016 at 8:00am
                                                                    </small>
                                                                    <p>
                                                                        Lorem ipsum dolor sit amet, consectetur
                                                                        adipisicing elit, sed do eiusmod tempor...
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-12 bord">
                                                            <div class="media">
                                                                <a class="pull-left" href="#">
                                                                    <img class="media-object thumbnail"
                                                                         src="{{asset('assets/img/authors/avatar4.jpg')}}" alt="image"
                                                                         height="80" width="80"/>
                                                                </a>
                                                                <div class="media-body">
                                                                    <h4 class="event-title">
                                                                        <a href="#">The standard chunk of Lorem
                                                                            Ipsum</a>
                                                                    </h4>
                                                                    <small class="text-muted">
                                                                        <i class="icon-location-pin icons"></i>
                                                                        Bay Area, San Francisco
                                                                    </small>
                                                                    <small class="text-muted">
                                                                        <i class="icon-calendar icons"></i>
                                                                        Saturday,Dec 24, 2016 at 8:00am
                                                                    </small>
                                                                    <p>
                                                                        Lorem ipsum dolor sit amet, consectetur
                                                                        adipisicing elit, sed do eiusmod tempor...
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <!-- media -->
                                                        </div>
                                                        <!-- col-sm-6 -->
                                                    </div>
                                                    <!-- row -->
                                                    <br/>
                                                    <h5 class="lg-title m-b-15">Past Events</h5>
                                                    <div class="row m-t-l-10">
                                                        <div class="col-sm-12 col-md-12 bord">
                                                            <div class="media">
                                                                <a class="pull-left" href="#">
                                                                    <img class="media-object thumbnail"
                                                                         src="{{asset('assets/img/authors/avatar5.jpg')}}" alt="image"
                                                                         height="80" width="80"/>
                                                                </a>
                                                                <div class="media-body">
                                                                    <h4 class="event-title">
                                                                        <a href="#">There are many variations</a>
                                                                    </h4>
                                                                    <small class="text-muted">
                                                                        <i class="icon-location-pin icons"></i>
                                                                        Silicon Valley, San Francisco, CA
                                                                    </small>
                                                                    <small class="text-muted">
                                                                        <i class="icon-calendar icons"></i>
                                                                        Sunday,Dec 15, 2015 at 11:00am
                                                                    </small>
                                                                    <p>
                                                                        Lorem ipsum dolor sit amet, consectetur
                                                                        adipisicing elit, sed do eiusmod tempor...
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <!-- media -->
                                                        </div>
                                                        <!-- col-sm-6 -->
                                                        <div class="col-sm-12 col-md-12 bord">
                                                            <div class="media">
                                                                <a class="pull-left" href="#">
                                                                    <img class="media-object thumbnail"
                                                                         src="{{asset('assets/img/authors/avatar6.jpg')}}" alt="image"
                                                                         height="80" width="80"/>
                                                                </a>
                                                                <div class="media-body">
                                                                    <h4 class="event-title">
                                                                        <a href="#">There are many variations of
                                                                            passages</a>
                                                                    </h4>
                                                                    <small class="text-muted">
                                                                        <i class="icon-location-pin icons"></i>
                                                                        New York City
                                                                    </small>
                                                                    <small class="text-muted">
                                                                        <i class="icon-calendar icons"></i>
                                                                        Monday,Dec 14, 2015 at 8:00am
                                                                    </small>
                                                                    <p>
                                                                        Lorem ipsum dolor sit amet, consectetur
                                                                        adipisicing elit, sed do eiusmod tempor...
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <!-- media -->
                                                        </div>
                                                        <!-- col-sm-6 -->
                                                        <div class="col-sm-12 col-md-12 bord">
                                                            <div class="media">
                                                                <a class="pull-left" href="#">
                                                                    <img class="media-object thumbnail"
                                                                         src="{{asset('assets/img/authors/avatar7.jpg')}}" alt="image"
                                                                         height="80" width="80"/>
                                                                </a>
                                                                <div class="media-body">
                                                                    <h4 class="event-title">
                                                                        <a href="#">English. Many desktop publishing</a>
                                                                    </h4>
                                                                    <small class="text-muted">
                                                                        <i class="icon-location-pin icons"></i>
                                                                        Los Angeles, CA
                                                                    </small>
                                                                    <small class="text-muted">
                                                                        <i class="icon-calendar icons"></i>
                                                                        Friday,Dec 12, 2015 at 8:00am
                                                                    </small>
                                                                    <p>
                                                                        Lorem ipsum dolor sit amet, consectetur
                                                                        adipisicing elit, sed do eiusmod tempor...
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <!-- media -->
                                                        </div>
                                                        <!-- col-sm-6 -->
                                                        <div class="col-sm-12 col-md-12 bord">
                                                            <div class="media">
                                                                <a class="pull-left" href="#">
                                                                    <img class="media-object thumbnail"
                                                                         src="{{asset('assets/img/authors/avataraa.jpg')}}" alt="image"
                                                                         height="80" width="80"/>
                                                                </a>
                                                                <div class="media-body">
                                                                    <h4 class="event-title">
                                                                        <a href="#">Lorem Ipsum comes from sections</a>
                                                                    </h4>
                                                                    <small class="text-muted">
                                                                        <i class="icon-location-pin icons"></i>
                                                                        Bay Area, San Francisco
                                                                    </small>
                                                                    <small class="text-muted">
                                                                        <i class="icon-calendar icons"></i>
                                                                        Saturday,Dec 10, 2015 at 8:00am
                                                                    </small>
                                                                    <p>
                                                                        Lorem ipsum dolor sit amet, consectetur
                                                                        adipisicing elit, sed do eiusmod tempor...
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <!-- media -->
                                                        </div>
                                                        <!-- col-sm-6 -->
                                                    </div>
                                                    <!-- row -->
                                                </div>
                                                <!-- events -->
                                            </div>
                                            <!-- tab-pane -->
                                        </div>
                                        <!-- tab-content -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--end of row--}}
            @include('layouts.right_sidebar')
        </section>
@stop

