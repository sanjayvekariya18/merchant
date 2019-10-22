@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show hase_option
    </h1>
    <br>
    <form method = 'get' action = '{!!url("hase_option")!!}'>
        <button class = 'btn btn-primary'>hase_option Index</button>
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
                    <b><i>option_id : </i></b>
                </td>
                <td>{!!$hase_option->option_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>merchant_id : </i></b>
                </td>
                <td>{!!$hase_option->merchant_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>location_id : </i></b>
                </td>
                <td>{!!$hase_option->location_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>option_name : </i></b>
                </td>
                <td>{!!$hase_option->option_name!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>display_type : </i></b>
                </td>
                <td>{!!$hase_option->display_type!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>priority : </i></b>
                </td>
                <td>{!!$hase_option->priority!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection