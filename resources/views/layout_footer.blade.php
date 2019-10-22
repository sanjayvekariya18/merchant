@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Footer
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" href="{{asset('assets/css/layouts.css')}}">
    <!-- end of page level css -->
@stop

{{-- Page content --}}
@section('content')
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Sticky Footer</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ URL :: to('index') }}">
                        <i class="fa fa-fw fa-home"></i> Dashboard
                    </a>
                </li>
                <li> Pages</li>
                <li class="active">
                    <a href="{{ URL :: to('blank') }}">Footer</a>
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content p-l-r-15">
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
                        <img src="http://placehold.it/1300x150" alt="Sample Image"
                             class="img-responsive Sample_placehold">
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
                    <p class="text-justify">
                        <img src="http://placehold.it/1300x150" alt="Sample Image"
                             class="img-responsive Sample_placehold">
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
            <!--footer-->
            <footer class="footer">
                <div class="row">
                    <div class="col-xs-6">
                        <p class="">© 2016 Core Plus<span class="hidden-xs">, All Rights Reserved</span>.</p>
                    </div>
                    <div class="col-xs-6">
                        <ul class="footer-list">
                            <li><a href="javascript:void(0)">Privacy</a></li>
                            <li class="hidden-xs"><a href="javascript:void(0)">Terms</a></li>
                            <li><a href="javascript:void(0)">Help</a></li>
                        </ul>
                    </div>
                </div>
            </footer>
            <!--footer-->

        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')

<script>
    $("[data-toggle='offcanvas']").on('click', function (e) {
        $(".footer").toggleClass('full-width');
    });
</script>
@stop