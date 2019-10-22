@extends('scaffold-interface.layouts.defaultMaterialize')
@section('title','Show')
@section('content')

<div class = 'container'>
    <h1>
        Show account_type
    </h1>
    <form method = 'get' action = '{!!url("account_type")!!}'>
        <button class = 'btn blue'>account_type Index</button>
    </form>
    <table class = 'highlight bordered'>
        <thead>
            <th>Key</th>
            <th>Value</th>
        </thead>
        <tbody>
            <tr>
                <td>
                    <b><i>type_id : </i></b>
                </td>
                <td>{!!$account_type->type_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>type_name : </i></b>
                </td>
                <td>{!!$account_type->type_name!!}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection