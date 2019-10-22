@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show hase_permission
    </h1>
    <br>
    <form method = 'get' action = '{!!url("hase_permission")!!}'>
        <button class = 'btn btn-primary'>hase_permission Index</button>
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
                    <b><i>permission_id : </i></b>
                </td>
                <td>{!!$hase_permission->permission_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>name : </i></b>
                </td>
                <td>{!!$hase_permission->name!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>description : </i></b>
                </td>
                <td>{!!$hase_permission->description!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>action : </i></b>
                </td>
                <td>{!!$hase_permission->action!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>status : </i></b>
                </td>
                <td>{!!$hase_permission->status!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection