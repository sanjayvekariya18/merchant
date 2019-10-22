@extends('scaffold-interface.layouts.app')
@section('title','Index')
@section('content')

<section class="content">
    <h1>
        Payee Index
    </h1>
    <form class = 'col s3' method = 'get' action = '{!!url("payee")!!}/create'>
        <button class = 'btn btn-primary' type = 'submit'>Create New payee</button>
    </form>
    <br>
    <br>
    <table class = "table table-striped table-bordered table-hover" style = 'background:#fff'>
        <thead>
            <th>payee_id</th>
            <th>identity_id</th>
            <th>identity_table_id</th>
            <th>postal_id</th>
            <th>actions</th>
        </thead>
        <tbody>
            @foreach($payees as $payee) 
            <tr>
                <td>{!!$payee->payee_id!!}</td>
                <td>{!!$payee->identity_id!!}</td>
                <td>{!!$payee->identity_table_id!!}</td>
                <td>{!!$payee->postal_id!!}</td>
                <td>
                    <a data-toggle="modal" data-target="#myModal" class = 'delete btn btn-danger btn-xs' data-link = "/payee/{!!$payee->id!!}/deleteMsg" ><i class = 'material-icons'>delete</i></a>
                    <a href = '#' class = 'viewEdit btn btn-primary btn-xs' data-link = '/payee/{!!$payee->id!!}/edit'><i class = 'material-icons'>edit</i></a>
                    <a href = '#' class = 'viewShow btn btn-warning btn-xs' data-link = '/payee/{!!$payee->id!!}'><i class = 'material-icons'>info</i></a>
                </td>
            </tr>
            @endforeach 
        </tbody>
    </table>
    {!! $payees->render() !!}

</section>
@endsection