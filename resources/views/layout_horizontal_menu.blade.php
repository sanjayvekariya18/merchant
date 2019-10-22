@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Horizontal Menu
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css-->
    <link rel="stylesheet" href="{{asset('assets/vendors/slimmenu/css/slimmenu.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/custom_css/horizontal_menu.css')}}">
    <!--end of page level css-->
@stop

{{-- Horizontal header content --}}
@section('horizontal_header')
    <div class="row horizontal_menu">
        <div class="col-md-12">
            <ul id="navigation" class="slimmenu">
                <li class="main-menu option-one"><a href="javascript:void(0)" class="menu-list">Dashboards</a>
                    <ul>
                        <li>
                            <a href="{{ URL :: to('index') }}" class="sub-list">
                                Dashboard V1
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL :: to('index2') }}" class="sub-list">
                                Dashboard V2
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="main-menu"><a href="javascript:void(0)" class="menu-list">Elements</a>
                    <ul>
                        <li><a href="javascript:void(0)" class="sub-list">Forms</a>
                            <ul>
                                <li>
                                    <a href="{{ URL :: to('form_elements') }}">
                                        Form Elements
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('form_validations') }}">
                                        Form Validations
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('form_layouts') }}">
                                        Form Layouts
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('form_wizards') }}">
                                        Form Wizards
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL:: to('radio_checkboxes') }}">
                                        Radio and Checkbox
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('advanceddate_pickers') }}">
                                        Advanced Date pickers
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('x-editable') }}">
                                        X-editable
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="sub-list">UI Features</a>
                            <ul>
                                <li>
                                    <a href="{{ URL :: to('general_components') }}">
                                        General Components
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL ::to('buttons') }}">
                                        Buttons
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('fonts') }}">
                                        Font Icons
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('advanced_modals') }}">
                                        Advanced Modals
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('gridstack') }}">
                                        Grid Stack
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('sweet_alert') }}">
                                        Sweet Alert
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('toastr_notifications') }}">
                                        Toastr Notifications
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('notifications') }}">
                                        Notifications
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li><a href="javascript:void(0)" class="sub-list">Components</a>
                            <ul>
                                <li>
                                    <a href="{{ URL :: to('timeline') }}">
                                        Timeline
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('transitions') }}">
                                        Transitions
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li><a href="javascript:void(0)" class="sub-list">Calendar</a>
                            <ul>
                                <li>
                                    <a href="{{ URL :: to('calendar') }}">
                                        Calendar
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('calendar2') }}">
                                        Advanced Calendar
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="main-menu"><a href="javascript:void(0)" class="menu-list">Data </a>
                    <ul>
                        <li>
                            <a href="javascript:void(0)" class="sub-list">Data Tables</a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="{{ URL :: to('simple_tables') }}">
                                        Simple tables
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to ('datatables') }}">
                                        Data Tables
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to ('advanced_datatables') }}">
                                        Advanced Tables
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('responsive_datatables') }}">
                                        Responsive DataTables
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('bootstrap_tables') }}">
                                        Bootstrap Tables
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="sub-list">Charts</a>
                            <ul>
                                <li>
                                    <a href="{{ URL :: to('flot_charts') }}">
                                        Flot Charts
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('nvd3_charts') }}">
                                        NVD3 Charts
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('circle_sliders') }}">
                                        Circle Sliders
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('chartjs_charts') }}">
                                        Chartjs Charts
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('dimple_charts') }}">
                                        Dimple Charts
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('amcharts') }}">
                                        Amcharts
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('chartist') }}">
                                        Chartist Charts
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="sub-list">Gallery</a>
                            <ul>
                                <li>
                                    <a href="{{ URL :: to('masonry_gallery') }}">
                                        <i class="fa fa-fw fa-file-image-o"></i> Masonry Gallery
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('multiplefile_upload') }}">
                                        <i class="fa fa-fw fa-cloud-upload"></i> Multiple File Upload
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('dropify') }}">
                                        <i class="fa fa-fw fa-dropbox"></i> Dropify
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('image_hover') }}">
                                        <i class="fa fa-file-image-o"></i> Image Hover
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('image_filter') }}">
                                        <i class="fa fa-filter"></i> Image Filter
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ URL :: to('image_magnifier') }}">
                                        <i class="fa  fa-search-plus"></i> Image Magnifier
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="main-menu"><a href="javascript:void(0)" class="menu-list">Maps </a>
                    <ul>
                        <li>
                            <a href="{{ URL :: to('google_maps') }}" class="sub-list">
                                Google Maps
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL :: to('vector_maps') }}" class="sub-list">
                                Vector Maps
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL :: to('advanced_maps') }}" class="sub-list">
                                Advanced Maps
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="main-menu"><a href="javascript:void(0)" class="menu-list">Pages </a>
                    <ul>
                        <li>
                            <a href="{{ URL :: to('lockscreen') }}" class="sub-list">
                                Lockscreen
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL :: to('invoice') }}" class="sub-list">
                                Invoice
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL :: to('blank') }}" class="sub-list">
                                Blank
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL :: to('login') }}" class="sub-list">
                                Login
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL :: to('register') }}" class="sub-list">
                                Register
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL :: to('404') }}" class="sub-list">
                                404 Error
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL :: to('500') }}" class="sub-list">
                                500 Error
                            </a>
                        </li>
                    </ul>
                </li>
                <li><a href="javascript:void(0)" class="menu-list active">Layouts </a>
                    <ul>
                        <li>
                            <a href="{{ URL :: to('menubarfold') }}" class="sub-list">
                                Menubar Fold
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL :: to('layout_horizontal_menu') }}" class="sub-list active">
                                Horizonta Menu
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL :: to('boxed') }}" class="sub-list">
                                Boxed Layout
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL :: to('layout_fixed_header') }}" class="sub-list">
                                Fixed Header
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL :: to('layout_boxed_fixed_header') }}" class="sub-list">
                                Boxed &amp; Fixed Header
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL :: to('layout_fixed') }}" class="sub-list">
                                Fixed Header &amp; Menu
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
@stop
{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Horizontal menu</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ URL :: to('index') }}">
                    <i class="fa fa-fw fa-home"></i> Dashboard
                </a>
            </li>
            <li> Layouts</li>
            <li class="active">
                <a href="{{ URL :: to('blank') }}">Horizontal menu</a>
            </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-12">
                <p class="text-justify">
                    Sed sed blandit urna. Proin ac sem nisl. Mauris risus orci, tristique eget velit at,
                    congue euismod
                    lacus. Curabitur id purus sit amet urna rutrum bibendum ac at quam. In hendrerit enim eu turpis
                    molestie, et euismod tellus viverra. Suspendisse molestie at leo sit amet volutpat. Integer
                    augue
                    libero, scelerisque vitae luctus ac, consequat et arcu. Nullam malesuada turpis eu ullamcorper
                    tincidunt. Integer aliquam felis eget neque facilisis ornare.
                </p>
                <p>
                    Integer pharetra vitae dolor vel
                    elementum. In nisl risus, dignissim non fermentum ac, pretium sit amet dui. Phasellus fringilla
                    orci
                    sapien, vel lacinia mi dapibus ut. Donec euismod congue nulla, in porttitor sapien. Pellentesque
                    facilisis luctus adipiscing.
                </p>
                <p class="text-justify">
                    <img src="http://placehold.it/1300x150" alt="Sample Image" class="img-responsive Sample_placehold">
                    Lorem ipsum
                    dolor sit amet, consectetur adipiscing elit. Nam massa eros, dictum ut imperdiet eget, laoreet
                    placerat orci. Aliquam eget neque neque. Donec dictum, enim convallis gravida fringilla, velit
                    sem
                    condimentum nunc, in pretium libero est sit amet elit. Nam ut arcu ac eros commodo rutrum ac nec
                    purus. Fusce sodales pulvinar odio, vulputate fringilla ligula bibendum sit amet. Duis risus
                    neque,
                    molestie tincidunt odio vel, sodales vulputate mauris. Sed adipiscing justo tristique enim
                    pharetra,
                    nec ultricies metus sagittis.Duis varius id massa ut pellentesque.
                </p>
                <p> Nulla commodo erat eu mi aliquet
                    aliquam. Vivamus commodo massa et nunc ullamcorper, vel pharetra purus consequat. Suspendisse a
                    neque quis nibh dictum posuere ac et enim. Aliquam sit amet accumsan erat. Nullam euismod elit
                    tellus, vel luctus enim luctus feugiat. Vestibulum quis placerat ipsum, porta vehicula massa.
                    Etiam
                    nec risus ac lacus gravida tincidunt. Vivamus eu ante vehicula, aliquam nisl et, suscipit ipsum.
                    Vivamus velit nulla, tincidunt ac risus et, congue lobortis mauris. In condimentum consectetur
                    purus, vel adipiscing felis sollicitudin vitae. Phasellus luctus, ligula eu tempor ullamcorper,
                    lectus elit posuere augue, eget tempus lacus nibh a purus. Ut risus velit, adipiscing eu leo
                    quis,
                    vestibulum porttitor nunc. Sed sed blandit urna. Proin ac sem nisl.
                </p>

                <div class="clearfix"></div>
            </div>
        </div>
        <!-- row -->
        @include('layouts.right_sidebar')
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script type="text/javascript" src="{{asset('assets/vendors/slimmenu/js/jquery.slimmenu.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/horizontal_menu.js')}}"></script>
    <!-- end of page level js -->
@stop