@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Customer Groups
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
        <h1>Show Customer Group</h1>
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
                Hase Customer Group Index
            </li>
        </ol>
    </section>
    <form method = 'get' action = '{!!url("hase_customer_group")!!}'>
        <button class = 'btn btn-primary'>hase_customer_group Index</button>
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
                    <b><i>customer_group_id : </i></b>
                </td>
                <td>{!!$hase_customer_group->customer_group_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>group_name : </i></b>
                </td>
                <td>{!!$hase_customer_group->group_name!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>description : </i></b>
                </td>
                <td>{!!$hase_customer_group->description!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>approval : </i></b>
                </td>
                <td>{!!$hase_customer_group->approval!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection