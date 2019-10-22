@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
        Multiple File Uplaod
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/blueimp-gallery-with-desc/css/blueimp-gallery.min.css')}}"/>
    <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/blueimp-file-upload/css/jquery.fileupload.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/blueimp-file-upload/css/jquery.fileupload-ui.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/dropify/css/dropify.css')}}">
    <!-- CSS adjustments for browsers with JavaScript disabled -->
    <noscript>
        <link rel="stylesheet" href="{{asset('assets/vendors/blueimp-file-upload/css/jquery.fileupload-noscript.css')}}"/>
        <link rel="stylesheet" href="{{asset('assets/vendors/blueimp-file-upload/css/jquery.fileupload-ui-noscript.css')}}"/>
    </noscript>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/multiplefile_upload.css')}}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Multiple File Uplaod
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-home"></i> Dashboard
                    </a>
                </li>
                <li> Gallery</li>
                <li class="active">
                        Multiple File Upload
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-upload"></i> Upload Multiple Files
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>
                                        File Upload widget with multiple file selection, progress bars, validation and
                                        preview images for jQuery.
                                    </h4>
                                    <form id="fileupload" method="POST" enctype="multipart/form-data">
                                        <!-- Redirect browsers with JavaScript disabled to the origin page -->
                                        <noscript>
                                            <input type="hidden" name="redirect" value="">
                                        </noscript>
                                        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                                        <div class="fileupload-buttonbar">
                                            <div class="col-lg-7">
                                                <!-- The fileinput-button span is used to style the file input field as button -->
                                                <span class="btn btn-success fileinput-button">
                                                    <i class="glyphicon glyphicon-plus"></i>
                                                    <span>Add files</span>
                                                    <input type="file" name="files[]" multiple>
                                                    </span>
                                                <button type="submit" class="btn btn-primary start">
                                                    <i class="glyphicon glyphicon-upload"></i>
                                                    <span>Start upload</span>
                                                </button>
                                                <button type="reset" class="btn btn-warning cancel">
                                                    <i class="glyphicon glyphicon-ban-circle"></i>
                                                    <span>Cancel upload</span>
                                                </button>
                                                <button type="button" class="btn btn-danger delete">
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                    <span>Delete</span>
                                                </button>
                                                <input type="checkbox" class="toggle">
                                                <!-- The global file processing state -->
                                                <span class="fileupload-process"></span>
                                            </div>
                                            <!-- The global progress state -->
                                            <div class="col-lg-5 fileupload-progress fade">
                                                <!-- The global progress bar -->
                                                <div class="progress progress-striped active" role="progressbar"
                                                     aria-valuemin="0" aria-valuemax="100">
                                                    <div class="progress-bar progress-bar-success"
                                                         style="width:0%;"></div>
                                                </div>
                                                <!-- The extended global progress state -->
                                                <div class="progress-extended">&nbsp;</div>
                                            </div>
                                        </div>
                                        <!-- The table listing the files available for upload/download -->
                                        <div class="table-responsive">
                                            <table role="presentation" class="table table-striped">
                                                <tbody class="files"></tbody>
                                            </table>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- without progress bar -->
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-tasks"></i> Without ProgressBar File Upload
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>
                                        Without ProgressBar
                                    </h4>
                                    <form id="noprogressupload" method="POST" enctype="multipart/form-data">
                                        <!-- Redirect browsers with JavaScript disabled to the origin page -->
                                        <noscript>
                                            <input type="hidden" name="redirect" value="">
                                        </noscript>
                                        <div class="fileupload-buttonbar">
                                            <div class="col-lg-7">
                                                <!-- The fileinput-button span is used to style the file input field as button -->
                                                <span class="btn btn-success fileinput-button">
                                                    <i class="glyphicon glyphicon-plus"></i>
                                                    <span>Add files</span>
                                                    <input type="file" name="files[]" multiple>
                                                    </span>
                                                <button type="submit" class="btn btn-primary start">
                                                    <i class="glyphicon glyphicon-upload"></i>
                                                    <span>Start upload</span>
                                                </button>
                                                <button type="reset" class="btn btn-warning cancel">
                                                    <i class="glyphicon glyphicon-ban-circle"></i>
                                                    <span>Cancel upload</span>
                                                </button>
                                                <button type="button" class="btn btn-danger delete">
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                    <span>Delete</span>
                                                </button>
                                                <input type="checkbox" class="toggle">
                                                <!-- The global file processing state -->
                                                <span class="fileupload-process"></span>
                                            </div>
                                        </div>
                                        <!-- The table listing the files available for upload/download -->
                                        <div class="table-responsive">
                                            <table role="presentation" class="table table-striped">
                                                <tbody class="files"></tbody>
                                            </table>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- end of without progress bar  -->
                        </div>
                    </div>
                    <!-- without delete button -->
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-filter"></i> Without Delete Button File Upload
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>
                                        Without Delete Button
                                    </h4>
                                    <form id="nodelete_button" method="POST" enctype="multipart/form-data">
                                        <!-- Redirect browsers with JavaScript disabled to the origin page -->
                                        <noscript>
                                            <input type="hidden" name="redirect" value="">
                                        </noscript>
                                        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                                        <div class="fileupload-buttonbar">
                                            <div class="col-lg-7">
                                                <!-- The fileinput-button span is used to style the file input field as button -->
                                                <span class="btn btn-success fileinput-button">
                                                    <i class="glyphicon glyphicon-plus"></i>
                                                    <span>Add files</span>
                                                    <input type="file" name="files[]" multiple>
                                                    </span>
                                                <button type="submit" class="btn btn-primary start">
                                                    <i class="glyphicon glyphicon-upload"></i>
                                                    <span>Start upload</span>
                                                </button>
                                                <button type="reset" class="btn btn-warning cancel">
                                                    <i class="glyphicon glyphicon-ban-circle"></i>
                                                    <span>Cancel upload</span>
                                                </button>
                                                <!-- The global file processing state -->
                                                <span class="fileupload-process"></span>
                                            </div>
                                            <!-- The global progress state -->
                                            <div class="col-lg-5 fileupload-progress fade">
                                                <!-- The global progress bar -->
                                                <div class="progress progress-striped active" role="progressbar"
                                                     aria-valuemin="0" aria-valuemax="100">
                                                    <div class="progress-bar progress-bar-success"
                                                         style="width:0%;"></div>
                                                </div>
                                                <!-- The extended global progress state -->
                                                <div class="progress-extended">&nbsp;</div>
                                            </div>
                                        </div>
                                        <!-- The table listing the files available for upload/download -->
                                        <div class="table-responsive">
                                            <table role="presentation" class="table table-striped">
                                                <tbody class="files"></tbody>
                                            </table>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- end of without delete button -->
                        </div>
                    </div>
                    <!-- auto upload -->
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-folder-open"></i> Auto Files Upload
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>
                                        Auto Upload
                                    </h4>
                                    <form id="autoupload" method="POST" enctype="multipart/form-data">
                                        <!-- Redirect browsers with JavaScript disabled to the origin page -->
                                        <noscript>
                                            <input type="hidden" name="redirect" value="">
                                        </noscript>
                                        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                                        <div class="fileupload-buttonbar">
                                            <div class="col-lg-7">
                                                <!-- The fileinput-button span is used to style the file input field as button -->
                                                <span class="btn btn-success fileinput-button">
                                                    <i class="glyphicon glyphicon-plus"></i>
                                                    <span>Add files</span>
                                                    <input type="file" name="files[]" multiple>
                                                    </span>
                                                <button type="reset" class="btn btn-warning cancel">
                                                    <i class="glyphicon glyphicon-ban-circle"></i>
                                                    <span>Cancel upload</span>
                                                </button>
                                                <button type="button" class="btn btn-danger delete">
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                    <span>Delete</span>
                                                </button>
                                                <input type="checkbox" class="toggle">
                                                <!-- The global file processing state -->
                                                <span class="fileupload-process"></span>
                                            </div>
                                            <!-- The global progress state -->
                                            <div class="col-lg-5 fileupload-progress fade">
                                                <!-- The global progress bar -->
                                                <div class="progress progress-striped active" role="progressbar"
                                                     aria-valuemin="0" aria-valuemax="100">
                                                    <div class="progress-bar progress-bar-success"
                                                         style="width:0%;"></div>
                                                </div>
                                                <!-- The extended global progress state -->
                                                <div class="progress-extended">&nbsp;</div>
                                            </div>
                                        </div>
                                        <!-- The table listing the files available for upload/download -->
                                        <div class="table-responsive">
                                            <table role="presentation" class="table table-striped">
                                                <tbody class="files"></tbody>
                                            </table>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- end of auto upload -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- row -->
        @include('layouts.right_sidebar')
        <!-- right side bar end -->
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/blueimp-file-upload/js/jquery.ui.widget.js')}}"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script type="text/javascript" src="{{asset('assets/vendors/blueimp-tmpl/js/tmpl.min.js')}}"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script type="text/javascript" src="{{asset('assets/vendors/blueimploadimage/js/load-image.all.min.js')}}"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script type="text/javascript" src="{{asset('assets/vendors/blueimp-canvas-to-blob/js/canvas-to-blob.min.js')}}"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<script type="text/javascript" src="{{asset('assets/vendors/blueimp-gallery-with-desc/js/jquery.blueimp-gallery.min.js')}}"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script type="text/javascript" src="{{asset('assets/vendors/blueimp-file-upload/js/jquery.iframe-transport.js')}}"></script>
<!-- The basic File Upload plugin -->
<script type="text/javascript" src="{{asset('assets/vendors/blueimp-file-upload/js/jquery.fileupload.js')}}"></script>
<!-- The File Upload processing plugin -->
<script type="text/javascript" src="{{asset('assets/vendors/blueimp-file-upload/js/jquery.fileupload-process.js')}}"></script>
<!-- The File Upload image preview & resize plugin -->
<script type="text/javascript" src="{{asset('assets/vendors/blueimp-file-upload/js/jquery.fileupload-image.js')}}"></script>
<!-- The File Upload audio preview plugin -->
<script type="text/javascript" src="{{asset('assets/vendors/blueimp-file-upload/js/jquery.fileupload-audio.js')}}"></script>
<!-- The File Upload video preview plugin -->
<script type="text/javascript" src="{{asset('assets/vendors/blueimp-file-upload/js/jquery.fileupload-video.js')}}"></script>
<!-- The File Upload validation plugin -->
<script type="text/javascript" src="{{asset('assets/vendors/blueimp-file-upload/js/jquery.fileupload-validate.js')}}"></script>
<!-- The File Upload user interface plugin -->
<script type="text/javascript" src="{{asset('assets/vendors/blueimp-file-upload/js/jquery.fileupload-ui.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/dropify/js/dropify.js')}}"></script>
<!-- end of page level js -->
<script>
    $(document).ready(function () {
        $('#fileupload').fileupload({
            dataType: 'json',
            maxNumberOfFiles: 4
        });
        $("#noprogressupload").fileupload({
            dataType: 'json',
            maxNumberOfFiles: 5,
            // autoUpload: true,
            uploadTemplateId: "noprogressbar"
        });
        $("#nodelete_button").fileupload({
            dataType: 'json',
            maxNumberOfFiles: 5,
            // autoUpload: true,
            downloadTemplateId: "nodeletebutton"
        });
        $("#autoupload").fileupload({
            dataType: 'json',
            maxNumberOfFiles: 5,
            autoUpload: true
        });
        $('.dropify').dropify();
    });
</script>
<script id="template-upload" type="text/x-tmpl">
        {% for (var i=0, file; file=o.files[i]; i++) { %}
        <tr class="template-upload fade">
            <td>
                <span class="preview"></span>
            </td>
            <td>
                <p class="name">{%=file.name%}</p>
                <strong class="error text-danger"></strong>
            </td>
            <td>
                <p class="size">Processing...</p>
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                </div>
            </td>
            <td>
                {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start m-t-10" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
                {% } %} {% if (!i) { %}
                <button class="btn btn-warning cancel m-t-10">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
                {% } %}
            </td>
        </tr>
        {% } %}



</script>
<script id="noprogressbar" type="text/x-tmpl">
        {% for (var i=0, file; file=o.files[i]; i++) { %}
        <tr class="template-upload fade">
            <td>
                <span class="preview"></span>
            </td>
            <td>
                <p class="name">{%=file.name%}</p>
                <strong class="error text-danger"></strong>
            </td>
            <td>
            </td>
            <td>
                {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start m-t-10" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
                {% } %} {% if (!i) { %}
                <button class="btn btn-warning cancel m-t-10">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
                {% } %}
            </td>
        </tr>
        {% } %}



</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
        {% for (var i=0, file; file=o.files[i]; i++) { %}
        <tr class="template-download fade">
            <td>
                <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
            </td>
            <td>
                <p class="name">
                    {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl? 'data-gallery': ''%}>{%=file.name%}</a> {% } else { %}
                    <span>{%=file.name%}</span> {% } %}
                </p>
                {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
                {% } %}
            </td>
            <td>
                <span class="size">{%=o.formatFileSize(file.size)%}</span>
            </td>
            <td>
                {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete m-t-10" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}" {% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}' {% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle"> {% } else { %}
                <button class="btn btn-warning cancel m-t-10">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
                {% } %}
            </td>
        </tr>
        {% } %}



</script>
<!-- no delete Button -->
<script id="nodeletebutton" type="text/x-tmpl">
        {% for (var i=0, file; file=o.files[i]; i++) { %}
        <tr class="template-download fade">
            <td>
                <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
            </td>
            <td>
                <p class="name">
                    {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl? 'data-gallery': ''%}>{%=file.name%}</a> {% } else { %}
                    <span>{%=file.name%}</span> {% } %}
                </p>
                {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
                {% } %}
            </td>
            <td>
                <span class="size">{%=o.formatFileSize(file.size)%}</span>
            </td>
            <td>
                <button class="btn btn-warning cancel m-t-10">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            </td>
        </tr>
        {% } %}



</script>
<!-- no delete button end -->
    <!-- end of page level js ->
    @stop
