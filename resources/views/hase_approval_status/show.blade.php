@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show hase_approval_status
    </h1>
    <br>
    <form method = 'get' action = '{!!url("hase_approval_status")!!}'>
        <button class = 'btn btn-primary'>hase_approval_status Index</button>
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
                    <b><i>status_id : </i></b>
                </td>
                <td>{!!$hase_approval_status->status_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>status_name : </i></b>
                </td>
                <td>{!!$hase_approval_status->status_name!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>status_display : </i></b>
                </td>
                <td>{!!$hase_approval_status->status_display!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection