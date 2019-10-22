@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show tax_type
    </h1>
    <br>
    <form method = 'get' action = '{!!url("tax_type")!!}'>
        <button class = 'btn btn-primary'>tax_type Index</button>
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
                    <b><i>type_id : </i></b>
                </td>
                <td>{!!$tax_type->type_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>type_code : </i></b>
                </td>
                <td>{!!$tax_type->type_code!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>type_name : </i></b>
                </td>
                <td>{!!$tax_type->type_name!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection