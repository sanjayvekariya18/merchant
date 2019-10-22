<!DOCTYPE html>
<html>

<head>
    <title>Lockscreen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <!-- Bootstrap -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- styles -->
    <!--page level css -->
    <link href="{{asset('assets/css/lockscreen.css')}}" rel="stylesheet">
    <!--end page level css-->
</head>
<body>
<div class="preloader">
    <div class="loader_img"><img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64"></div>
</div>
<div class="top">
    <div class="colors"></div>
</div>
<div class="container">
    <div class="lockscreen-container">
        <div id="output"></div>
        <div class="avatar"></div>
        <div class="form-box">
            <form action="#" method="post">
                <div class="form">
                    <p class="form-control-static">ADMIN</p>
                    <input type="password" name="user" class="form-control" placeholder="Password">
                    <button class="btn btn-info btn-block login" id="index" type="submit">GO</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- global js -->
<script src="{{asset('assets/js/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/bootstrap.min.js')}}" type="text/javascript"></script>
<!-- end of global js -->
<!-- page css -->
<script src="{{asset('assets/js/lockscreen.js')}}"></script>
<!-- end of page css -->
</body>

</html>