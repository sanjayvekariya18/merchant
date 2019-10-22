@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show trade_side_type
    </h1>
    <br>
    <form method = 'get' action = '{!!url("trade_side_type")!!}'>
        <button class = 'btn btn-primary'>trade_side_type Index</button>
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
                    <b><i>side_type_id : </i></b>
                </td>
                <td>{!!$trade_side_type->side_type_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>side_type_code : </i></b>
                </td>
                <td>{!!$trade_side_type->side_type_code!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>side_type_name : </i></b>
                </td>
                <td>{!!$trade_side_type->side_type_name!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection