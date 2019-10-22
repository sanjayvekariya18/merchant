@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Categories
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')

    <!--page level css -->
    
    <link href="{{asset('assets/vendors/bootstrap-fileinput/css/fileinput.min.css')}}" media="all" rel="stylesheet" type="text/css"/>
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Edit Uploaded File</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> System</a>
        </li>
        <li class="active">
            Imports
        </li>
    </ol>
</section>
<section class="content">
    <form id="importForm"  action = '{!! url("hase_import")!!}/{!!$hase_import->
        import_id!!}/update' method = 'POST' class="form-horizontal" role="form" enctype="multipart/form-data">
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <input type = 'hidden' name = 'import_id' value='{!!$hase_import->import_id!!}'>
        <input type = 'hidden' id="fileName" value="<?= basename($hase_import->slug)?>">

        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
                <a href="{!!url("hase_import")!!}" class='btn btn-primary btn-inline'>
                    <i class="fa fa-fw fa-arrow-left"></i>
                </a>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-fw fa-crosshairs"></i> Upload Form
                        </h3>
                    </div>
                    <div class="panel-body">                        
                        <div class="form-group">
                            <label for="Name" class="col-sm-3 ">Name</label>
                            <div class="col-sm-4">
                                <select name="title" id="title" class="form-control">
                                    <option value="Resturant Merchant">Resturant Merchant</option>
                                    <option value="Resturant Working Hours">Resturant Working Hours</option>
                                    <option value="Resturant Holiday Working Hours">Resturant Holiday Working Hours</option>
                                    <option value="Resturant Menus">Resturant Menus</option>
                                    <option value="Shop Merchant">Shop Merchant</option>
                                    <option value="Shop Working Hours">Shop Working Hours</option>
                                    <option value="Shop Holiday Working Hours">Shop Holiday Working Hours</option>
                                    <option value="Shop Menus">Shop Menus</option>
                                </select>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="input-23" class="col-sm-3 ">Upload File</label>
                            <div class="col-sm-6">
                                <input id="input-23" name="upload_file" type="file" class="file-loading" data-show-preview="false" value="">
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection
{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->

<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/js/custom_js/haseImportsEdit.js')}}"></script>
@stop