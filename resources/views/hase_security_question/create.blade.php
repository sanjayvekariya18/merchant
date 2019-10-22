@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Hase Security Questions
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <!--end of page level css-->
@stop
@section('content')

<section class="content">
    <section class="content-header">
        <h1>Hase Customer Groups</h1>
        <ol class="breadcrumb">
            <li>
                <a href="index ">
                    <i class="fa fa-fw fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="#"> Users</a>
            </li>
            <li class="active">
                Hase_security_question Index
            </li>
        </ol>
    </section>
    <form method = 'get' action = '{!!url("hase_security_question")!!}'>
        <button class = 'btn btn-danger'>hase_security_question Index</button>
    </form>
    <br>
    <form method = 'POST' action = '{!!url("hase_security_question")!!}'>
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <div class="form-group">
            <label for="question_id">question_id</label>
            <input id="question_id" name = "question_id" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="text">text</label>
            <input id="text" name = "text" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="priority">priority</label>
            <input id="priority" name = "priority" type="text" class="form-control">
        </div>
        <button class = 'btn btn-primary' type ='submit'>Create</button>
    </form>
</section>
@endsection