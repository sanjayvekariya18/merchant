@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Hase Security Questions
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <!--end of page level css-->
@stop
@section('content')

<section class="content">
    <section class="content-header">
        <h1>Hase Security Questions</h1>
        <ol class="breadcrumb">
            <li>
                <a href="index ">
                    <i class="fa fa-fw fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="#"> Localisation</a>
            </li>
            <li class="active">
                Hase Security Question Index
            </li>
        </ol>
    </section>
    <form id='update_question_form' method = 'POST' action = '{!! url("hase_security_question")!!}/update'>
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <input id="question_count" name = "question_count" type="hidden" class="form-control" value="{{$question_count}}">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
            </div>
        </div>
        <br/>
        <div class="table-responsive">
            <table class="table table-striped table-border table-sortable" id="table">
                <thead>
                    <tr>
                        <th class="action action-one"></th>
                        <th class="action action-one"></th>
                        <th class="id">ID</th>
                        <th>Question</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($hase_security_questions as $questionKey => $security_questions_value)
                <?php 
                $questionKey++;
                ?>
                    <tr id="table-row{{$questionKey}}">
                        <td class="action action-one" style="width: 2.33% !important;">
                            <i class="fa fa-sort handle"></i>
                        </td>
                        <td class="action action-one" style="width: 4.33% !important;">
                            <!-- <a class="btn btn-danger" onclick="confirm('This cannot be undone! Are you sure you want to do this?') ? $(this).parent().parent().remove() : false;"><i class="fa fa-times-circle"></i></a> -->
                            <a href='#' class="btn btn-danger" data-toggle="modal" data-target="#delete" data-link = '{!!url("hase_security_question")!!}/{!!$security_questions_value->question_id!!}/delete'><i class="fa fa-times-circle"></i></a>
                        </td>
                        <td class="id">     
                            <input type="hidden" id="question_id" name="questions[{{$questionKey}}][question_id]" class="form-control" value="{!!$security_questions_value->question_id!!}" >{!!$security_questions_value->question_id!!}
                        </td>
                        <td>        
                            <input type="text" id="value" name="questions[{{$questionKey}}][text]" class="form-control questionRequired" placeholder="The Question Value field is required.." value="{!!$security_questions_value->text!!}" required="required">
                        </td>   
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr id="tfoot">
                        <td class="action action-one">
                            <a class="btn btn-primary" onclick="DynamicSecurityQuestionRow({!!$security_questions_value['question_id']!!},{{$questionKey}});">
                                <i class="fa fa-plus"></i>
                            </a>
                        </td>
                        <td colspan="5">
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div id="DynamicSecurityQuestionRow">
            <table>
                <tr id="table-row">
                    <td class="action action-one">
                        <i class="fa fa-sort handle"></i>
                    </td>
                    <td class="action action-one" style="width: 4.33% !important;">
                        <a class="btn btn-danger" onclick="confirm('This cannot be undone! Are you sure you want to do this?') ? $(this).parent().parent().remove() : false;"><i class="fa fa-times-circle"></i></a>
                    </td>
                    <td>        
                        <input type="text" id="text" name="text" class="form-control" placeholder="The Question Value field is required.." value="">
                    </td>    
                    <td class="id">     
                        <input type="hidden" id="question_id" name="question_id" class="form-control" >-
                    </td>
                </tr>
            </table>
        </div>
        <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="Heading" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title custom_align" id="Heading">Delete Security Question</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Question?
                            </div>
                        </div>
                        <div class="modal-footer ">
                            <a href="#" class="btn btn-danger">
                                <span class="glyphicon glyphicon-ok-sign"></span> Yes
                            </a>
                            <button type="button" class="btn btn-success" data-dismiss="modal">
                                <span class="glyphicon glyphicon-remove"></span> No
                            </button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
            </div>
            <!-- /.modal-dialog -->
    </form>
</section>
@include('layouts.right_sidebar')

</section>
@endsection
{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/jquery.dataTables.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.bootstrap.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/js/custom_js/haseQuestions.js')}}"></script>
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