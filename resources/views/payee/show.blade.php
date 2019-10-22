@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show payee
    </h1>
    <br>
    <form method = 'get' action = '{!!url("payee")!!}'>
        <button class = 'btn btn-primary'>payee Index</button>
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
                    <b><i>payee_id : </i></b>
                </td>
                <td>{!!$payee->payee_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>identity_id : </i></b>
                </td>
                <td>{!!$payee->identity_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>identity_table_id : </i></b>
                </td>
                <td>{!!$payee->identity_table_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>postal_id : </i></b>
                </td>
                <td>{!!$payee->postal_id!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection