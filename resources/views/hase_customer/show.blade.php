@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Customers
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
        <h1>Show Customers</h1>
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
               customer Index
            </li>
        </ol>
    </section>
    <form method = 'get' action = '{!!url("hase_customer")!!}'>
        <button class = 'btn btn-primary'>hase_customer Index</button>
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
                    <b><i>customer_id : </i></b>
                </td>
                <td>{!!$hase_customer->customer_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>first_name : </i></b>
                </td>
                <td>{!!$hase_customer->first_name!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>last_name : </i></b>
                </td>
                <td>{!!$hase_customer->last_name!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>email : </i></b>
                </td>
                <td>{!!$hase_customer->email!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>password : </i></b>
                </td>
                <td>{!!$hase_customer->password!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>salt : </i></b>
                </td>
                <td>{!!$hase_customer->salt!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>telephone : </i></b>
                </td>
                <td>{!!$hase_customer->telephone!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>security_question_id : </i></b>
                </td>
                <td>{!!$hase_customer->security_question_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>security_answer : </i></b>
                </td>
                <td>{!!$hase_customer->security_answer!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>newsletter : </i></b>
                </td>
                <td>{!!$hase_customer->newsletter!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>customer_group_id : </i></b>
                </td>
                <td>{!!$hase_customer->customer_group_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>ip_address : </i></b>
                </td>
                <td>{!!$hase_customer->ip_address!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>date_added : </i></b>
                </td>
                <td>{!!$hase_customer->date_added!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>status : </i></b>
                </td>
                <td>{!!$hase_customer->status!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>cart : </i></b>
                </td>
                <td>{!!$hase_customer->cart!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection