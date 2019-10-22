@php use App\Http\Traits\PermissionTrait; @endphp
@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Language Identities
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
<link rel="stylesheet" href="assets/kendoui/styles/kendo.common.min.css">
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.rtl.min.css">
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.default.min.css">
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.mobile.all.min.css">
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.blueopal.min.css" />
@stop
@section('content')
<section class="content-header">
    <h1>Language Identities List</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> system </a>
        </li>
        <li class="active">
            Language Identities
        </li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <a href='{!!url("users_language")!!}/create' class='btn btn-primary btn-inline'>Create New Language Identities</a>
           
        </div>
    </div>
    <br>
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i>Language Identities List
                    </h4>
                </div>
<div id="language_identitites_grid"></div>
            </div>

            
            
        </div>
    </section>
</section>
@endsection

{{-- page level scripts --}}
@section('footer_scripts')
<script src="assets/kendoui/js/kendo.all.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/languageIdentities.js')}}"></script>
<script type="text/javascript">    
        languageIdentititesList();
</script>
@stop
