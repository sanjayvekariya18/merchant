<!DOCTYPE html>
<html>

<head>
    <title>::500 Page::</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}" />
    <!-- global level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap.min.css')}}">
    <!-- end of global css-->
    <!-- page level styles-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/404.css')}}" />
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
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-sm-push-6">
                        <div class="error_type text-center hidden-lg hidden-md hidden-sm">500</div>
                        <p class="error text-center hidden-lg hidden-md hidden-sm">error</p>
                        <div class="text-center server_img"><img src="{{asset('assets/img/pages/500.png')}}" alt="server break"></div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-sm-pull-6">
                        <div class="text-center">
                            <div class="error_type hidden-xs">500</div>
                            <p class="error hidden-xs">error</p>
                            <div class="error_msg"><p>Oops! Something wrong with Internal Server</p></div>
                            <hr class="seperator">
                            <a href="index" class="btn btn-primary">Go Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- global js -->
<script type="text/javascript" src="{{asset('assets/js/jquery.min.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/js/bootstrap.min.js')}}"></script>
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
