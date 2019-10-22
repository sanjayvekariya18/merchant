<!DOCTYPE html>
<html>

<head>
    <title>::404 Page::</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <!-- global level css -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- end of global css-->
    <!-- page level styles-->
    <link href="{{asset('assets/css/404.css')}}" rel="stylesheet">
    <!-- end of page level styles-->
</head>

<body>
<div class="preloader">
    <div class="loader_img"><img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64"></div>
</div>
<div class="container">
    <div class="row">
        <div class="err-cont">
            <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
                <div class="col-sm-6">
                    <div class="error_type text-center hidden-lg hidden-md hidden-sm">404</div>
                    <p class="error text-center hidden-lg hidden-md hidden-sm">error</p>
                    <div class="text-center robot"><img src="{{asset('assets/img/pages/404.png')}}" alt="server break"></div>
                </div>
                <div class="col-sm-6">
                    <div class="text-center">
                        <div class="error_type hidden-xs">404</div>
                        <p class="error hidden-xs">error</p>
                        <div class="error_msg"><p>Sorry, The page is missing</p></div>
                        <hr class="seperator">
                        <a href="index" class="btn btn-primary">Go Home</a>
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
<script type="text/javascript">
    //=================Preloader===========//
    $(window).on('load', function () {
        $('.preloader img').fadeOut();
        $('.preloader').fadeOut();
    });
    //=================end of Preloader===========//
</script>
</body>

</html>

