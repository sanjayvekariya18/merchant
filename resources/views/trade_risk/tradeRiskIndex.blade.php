@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Trade Risk
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
        <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
        <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.uniform.min.css">
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Trade Risk</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#">Trade Risk</a>
        </li>
        <li class="active">
            Risk
        </li>
    </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
      <div class="row">
         <div class="panel panel-primary ">
            <div class="panel-heading">
               <h4 class="panel-title">    
                  <i class="fa fa-fw fa-users"></i> Trade Risk
               </h4>
            </div>
            <div class="panel-body">
               <div id="customerPostionGrid"></div>
            </div> 
            <script type="text/javascript" src="{{asset('assets/js/custom_js/tradeRisk.js')}}"></script>
            <script type="text/javascript">
               TradeRiskListData();
            </script>
         </div>
      </div>
   </section>
</section>
@endsection
{{-- page level scripts --}}
<script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
<script src="assets/kendoui/js/kendo.all.min.js" type="text/javascript"></script>
@section('footer_scripts')

<script type="text/javascript">
    
    @if(Session::has('type'))
        toastr.options = {
            "closeButton": true,
            "positionClass": "toast-top-right",
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "swing",
            "showMethod": "show"
        };
        var $toast = toastr["{{ Session::pull('type') }}"]("", "{{ Session::pull('msg') }}");
    @endif

</script>
@stop