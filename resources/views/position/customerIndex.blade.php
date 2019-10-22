@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Trade Positions
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
        <link rel="stylesheet" type="text/css" href="{{asset('assets/kendoui/styles/kendo.common.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/kendoui/styles/kendo.uniform.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/kendoui/styles/kendo.mobile.all.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/kendoui/styles/kendo.blueopal.min.css')}}">
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Trade Positions</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#">Trade Positions</a>
        </li>
        <li class="active">
            Position
        </li>
    </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
      <div class="row">
         <div class="panel panel-primary ">
            <div class="panel-heading">
               <h4 class="panel-title">    
                  <i class="fa fa-fw fa-users"></i> Trade Positions
               </h4>
            </div>
            <div class="panel-body">
               <div id="customerPostionGrid"></div>
            </div> 
            <script type="text/javascript" src="{{asset('assets/js/custom_js/customerPosition.js')}}"></script>
            <script type="text/javascript">
               customerPostionListData();
            </script>
         </div>
      </div>
   </section>
</section>
@endsection
{{-- page level scripts --}}
<script src="{{asset('assets/kendoui/js/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/kendoui/js/kendo.all.min.js')}}" type="text/javascript"></script>
@section('footer_scripts')
@stop