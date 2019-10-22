@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Show Table
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
        <h1>Show Table</h1>
        <ol class="breadcrumb">
            <li>
                <a href="index ">
                    <i class="fa fa-fw fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="#"> Restaurant</a>
            </li>
            <li class="active">
                Show Table
            </li>
        </ol>
    </section>
    <br>
    <form method = 'get' action = '{!!url("hase_table")!!}'>
        <button class = 'btn btn-primary'>hase_table Index</button>
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
                    <b><i>table_id : </i></b>
                </td>
                <td>{!!$hase_table->table_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>merchant_id : </i></b>
                </td>
                <td>{!!$hase_table->merchant_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>table_name : </i></b>
                </td>
                <td>{!!$hase_table->table_name!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>min_capacity : </i></b>
                </td>
                <td>{!!$hase_table->min_capacity!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>max_capacity : </i></b>
                </td>
                <td>{!!$hase_table->max_capacity!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>autobook : </i></b>
                </td>
                <td>{!!$hase_table->autobook!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>table_status : </i></b>
                </td>
                <td>{!!$hase_table->table_status!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection