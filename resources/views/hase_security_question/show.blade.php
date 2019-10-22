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
        <button class = 'btn btn-primary'>hase_security_question Index</button>
    </form>
    <br>
    <table class = 'table table-bordered'>
        <thead>
            <th>Key</th>
            <th>Value</th>
        </thead>
        <tbody>
            <tr>
                <td>
                    <b><i>question_id : </i></b>
                </td>
                <td>{!!$hase_security_question->question_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>text : </i></b>
                </td>
                <td>{!!$hase_security_question->text!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>priority : </i></b>
                </td>
                <td>{!!$hase_security_question->priority!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection