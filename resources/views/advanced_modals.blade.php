@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Advanced Modals
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/hover/css/hover-min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/animate/animate.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/advanced_modals.css')}}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
        <section class="content-header">
            <!--section starts-->
            <h1>Advanced Modals</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-home"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="#">
                        UI Features
                    </a>
                </li>
                <li class="active">
                    Advanced Modals
                </li>
            </ol>
        </section>
        <section class="content">
            <!--main content-->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-gear"></i> Modal Styles
                            </h3>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <h4>Basic Modal</h4>
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#basic_modal">
                                Open Basic Modal
                            </button>
                            <!-- Modal Animate Buttons -->
                            <h4>Animated Modal Entrances</h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-3">
                                            <button type="button" class="btn btn-info btn-block" data-toggle="modal"
                                                    data-target="#basic_modal" data-animate-modal="fadeIn">Fade In
                                            </button>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <button type="button" class="btn btn-info btn-block" data-toggle="modal"
                                                    data-target="#basic_modal" data-animate-modal="slideInRight">Slide
                                                In Right
                                            </button>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <button type="button" class="btn btn-info btn-block" data-toggle="modal"
                                                    data-target="#basic_modal" data-animate-modal="slideInUp">Slide From
                                                Bottom
                                            </button>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <button type="button" class="btn btn-info btn-block" data-toggle="modal"
                                                    data-target="#basic_modal" data-animate-modal="jello">Jello
                                            </button>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <button type="button" class="btn btn-info btn-block" data-toggle="modal"
                                                    data-target="#basic_modal" data-animate-modal="bounceInLeft">Bounce
                                                In Left
                                            </button>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <button type="button" class="btn btn-info btn-block" data-toggle="modal"
                                                    data-target="#basic_modal" data-animate-modal="flip">Flip
                                            </button>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <button type="button" class="btn btn-info btn-block" data-toggle="modal"
                                                    data-target="#basic_modal" data-animate-modal="flipInX">Flip In
                                                Horizantlly
                                            </button>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <button type="button" class="btn btn-info btn-block" data-toggle="modal"
                                                    data-target="#basic_modal" data-animate-modal="flipInY">Flip In
                                                Vertically
                                            </button>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <button type="button" class="btn btn-info btn-block" data-toggle="modal"
                                                    data-target="#basic_modal" data-animate-modal="rotateInDownLeft">
                                                Rotate In
                                            </button>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <button type="button" class="btn btn-info btn-block" data-toggle="modal"
                                                    data-target="#basic_modal" data-animate-modal="lightSpeedIn">Light
                                                Speed
                                            </button>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <button type="button" class="btn btn-info btn-block" data-toggle="modal"
                                                    data-target="#basic_modal" data-animate-modal="wobble">Wobble
                                            </button>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <button type="button" class="btn btn-info btn-block" data-toggle="modal"
                                                    data-target="#basic_modal" data-animate-modal="zoomInDown">Zoom In
                                                Down
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- End of Animation buttons -->
                                <div id="basic_modal" class="modal fade animated" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close"
                                                        data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">Modal Header</h4>
                                            </div>
                                            <div class="modal-body">
                                                <p>This is the text in modal</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- modal position -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-gear"></i> Modal Positions
                            </h3>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <h4>Position Of Modal</h4>
                            <p>The different modal positions are Top, Center, Bottom and also as a Side-bar</p>
              <button type="button" class="btn btn-info topposition" data-toggle="modal"
                                    data-target="#top_modal">Top Modal
                            </button>
                            <div id="top_modal" class="modal fade animated position_modal" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Modal Header</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>This is the modal at top of the screen</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-info centerposition" data-toggle="modal"
                                    data-target="#center_modal">Center Modal
                            </button>
                            <div id="center_modal" class="modal fade animated position_modal" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Modal Header</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>This is the modal at center of the screen</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-info bottomposition" data-toggle="modal"
                                    data-target="#bottom_modal">Bottom Modal
                            </button>
                            <div id="bottom_modal" class="modal fade animated position_modal" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Modal Header</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>This is the modal at bottom of the screen</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-info sidebarmodal" data-toggle="modal"
                                    data-target="#sidebar_modal">Side-bar Modal
                            </button>
                            <div id="sidebar_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Modal Header</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>This is the text in modal</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of modal position -->
            <!-- additional Modals -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-gear"></i> Additional Modal
                            </h3>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <h4>Additional Contents of a Modal</h4>
                            <!-- form-modal -->
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#form_modal">
                                Form Modal
                            </button>
                            <div id="form_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Modal Header</h4>
                                        </div>
                                                   <form role="form">
                                            <div class="modal-body">
                                                <div class="row m-t-10">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <label class="sr-only" for="first-name">First-Name</label>
                                                            <input type="text" name="first-name" id="first-name"
                                                                   placeholder="First Name" class="form-control m-t-10">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <label class="sr-only" for="last-name">Last-Name</label>
                                                            <input type="text" name="last-name" id="last-name"
                                                                   placeholder="Last Name" class="form-control m-t-10">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row m-t-10">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="sr-only" for="message">Message</label>
                                                            <textarea class="form-control resize_vertical m-t-10"
                                                                      name="message"
                                                                      placeholder="Message" rows="6"
                                                                      id="message"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-succes">Submit</button>
                                                <button type="reset" class="btn btn-default">Reset</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                                    Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- form-modal end -->
                            <!-- Multiple Modals -->
                            <button type="button" class="btn btn-info" data-toggle="modal"
                                    data-target="#multiple_modal1">Multiple Modals
                            </button>
                            <div id="multiple_modal1" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Modal 1</h4>
                                        </div>
                                        <div class="modal-body">
                                            Click Next to go to next modal.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-info" data-target="#multiple_modal2"
                                                    data-toggle="modal" data-dismiss="modal">Next
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="multiple_modal2" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Modal 2</h4>
                                        </div>
                                        <div class="modal-body">
                                            This is the second modal
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- multiple modals end -->
                            <!-- modal sizes -->
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#large_modal">
                                Large Modal
                            </button>
                            <div id="large_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Large Modal</h4>
                                        </div>
                                        <div class="modal-body">
                                            This is a large modal.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#small_modal">
                                Small Modal
                            </button>
                            <div id="small_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Small Modal</h4>
                                        </div>
                                        <div class="modal-body">
                                            This is a small modal.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- modal sizes end -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- end additional modals -->
            <!-- bootstrap components in modals -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-gear"></i> Modal With Bootstrap Components
                            </h3>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <h4>Advanced Modal</h4>
                            <!-- modal with tabs -->
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#tabs_modal">
                                Modal With Tabs
                            </button>
                            <div id="tabs_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Modal With Tabs</h4>
                                        </div>
                                        <div class="modal-body">
                                            <h4>Tabs in a Modal</h4>
                                            <ul class="nav nav-tabs">
                                                <li class="active">
                                                    <a data-toggle="tab" href="#tab1">Tab 1</a>
                                                </li>
                                                <li>
                                                    <a data-toggle="tab" href="#tab2">Tab 2</a>
                                                </li>
                                                <li>
                                                    <a data-toggle="tab" href="#tab3">Tab 3</a>
                                                </li>
                                                <li>
                                                    <a data-toggle="tab" href="#tab4">Tab 4</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div id="tab1" class="tab-pane fade in active">
                                                    <h3>Tab 1</h3>
                                                    <p>First Tab</p>
                                                </div>
                                                <div id="tab2" class="tab-pane fade">
                                                    <h3>TAB 2</h3>
                                                    <p>Second Tab</p>
                                                </div>
                                                <div id="tab3" class="tab-pane fade">
                                                    <h3>TAB 3</h3>
                                                    <p>Third Tab</p>
                                                </div>
                                                <div id="tab4" class="tab-pane fade">
                                                    <h3>TAB 4</h3>
                                                    <p>Fourth Tab</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- modal with tabs end -->
                            <!-- modal with accordions -->
                            <button type="button" class="btn btn-info" data-toggle="modal"
                                    data-target="#accordions_modal">Accordions in a Modal
                            </button>
                            <div id="accordions_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Modal with Accordions</h4>
                                        </div>
                                        <div class="modal-body">
                                            <h4>Accordion In a Modal</h4>
                                            <div class="panel-group" id="accordion">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a data-toggle="collapse" data-parent="#accordion"
                                                               href="#collapse1">Collapsible Group 1</a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapse1" class="panel-collapse collapse in">
                                                        <div class="panel-body">Lorem ipsum dolor sit amet, consectetur
                                                            adipisicing elit, sed do eiusmod tempor incididunt ut labore
                                                            et dolore magna aliqua. Ut enim ad minim veniam, quis
                                                            nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                                                            commodo consequat.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a data-toggle="collapse" data-parent="#accordion"
                                                               href="#collapse2">Collapsible Group 2</a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapse2" class="panel-collapse collapse">
                                                        <div class="panel-body">Lorem ipsum dolor sit amet, consectetur
                                                            adipisicing elit, sed do eiusmod tempor incididunt ut labore
                                                            et dolore magna aliqua. Ut enim ad minim veniam, quis
                                                            nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                                                            commodo consequat.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a data-toggle="collapse" data-parent="#accordion"
                                                               href="#collapse3">Collapsible Group 3</a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapse3" class="panel-collapse collapse">
                                                        <div class="panel-body">Lorem ipsum dolor sit amet, consectetur
                                                            adipisicing elit, sed do eiusmod tempor incididunt ut labore
                                                            et dolore magna aliqua. Ut enim ad minim veniam, quis
                                                            nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                                                            commodo consequat.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- modal with accordions end-->
                            <!-- grid in a modal -->
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#grid_modal">
                                Modals with Grid
                            </button>
                            <div id="grid_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Modal with grid arrangement</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">col-md-6</div>
                                                <div class="col-md-6">col-md-6</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">col-md-12
                                                    <div class="row">
                                                        <div class="col-md-6">col-md-6</div>
                                                        <div class="col-md-6">col-md-6</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">col-md-6</div>
                                                <div class="col-md-6">col-md-6</div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- grid in a modal end-->
                            <!-- fill modal -->
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#fill_modal">
                                Just Me
                            </button>
                            <div id="fill_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Just Me</h4>
                                        </div>
                                        <div class="modal-body">
                                            This modal fills the entire page.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- fill modal end-->
                        </div>
                    </div>
                </div>
            </div>
            <!-- bootstrap components in modals end-->
            <!-- modals with colors -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-gear"></i> Modal Colors
                            </h3>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <h4>Color Of Modal</h4>
                            <p>We can set any color to a modal. Here are some examples..</p>
                            <button type="button" class="btn btn-default modalcolor" data-toggle="modal"
                                    data-target="#color_modal" data-modalcolor="#dcdcdc">Default Modal
                            </button>
                            <button type="button" class="btn btn-primary modalcolor" data-toggle="modal"
                                    data-target="#color_modal" data-modalcolor="#428bca">Primary Modal
                            </button>
                            <button type="button" class="btn btn-info modalcolor" data-toggle="modal"
                                    data-target="#color_modal" data-modalcolor="#4fc1e9">Info Modal
                            </button>
                            <button type="button" class="btn btn-success modalcolor" data-toggle="modal"
                                    data-target="#color_modal" data-modalcolor="#22d69d">Success Modal
                            </button>
                            <button type="button" class="btn btn-warning modalcolor" data-toggle="modal"
                                    data-target="#color_modal" data-modalcolor="#ffb65f">Warning Modal
                            </button>
                            <button type="button" class="btn btn-danger modalcolor" data-toggle="modal"
                                    data-target="#color_modal" data-modalcolor="#fb8678">Danger Modal
                            </button>
                            <div id="color_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Modal Header</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>This is the text in modal</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- modals with colors end-->
            <!--main content ends-->
            @include('layouts.right_sidebar')
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/js/custom_js/advanced_modals.js')}}"></script>
<!--end of page level js-->
@stop
