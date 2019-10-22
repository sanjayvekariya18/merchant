@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show hase_exhibition
    </h1>
    <br>
    <form method = 'get' action = '{!!url("hase_exhibition")!!}'>
        <button class = 'btn btn-primary'>hase_exhibition Index</button>
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
                    <b><i>exhibition_id : </i></b>
                </td>
                <td>{!!$hase_exhibition->exhibition_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>exhibition_name : </i></b>
                </td>
                <td>{!!$hase_exhibition->exhibition_name!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>exhibition_venue : </i></b>
                </td>
                <td>{!!$hase_exhibition->exhibition_venue!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>exhibition_date_start : </i></b>
                </td>
                <td>{!!$hase_exhibition->exhibition_date_start!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>exhibition_date_end : </i></b>
                </td>
                <td>{!!$hase_exhibition->exhibition_date_end!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>exhibition_time_start : </i></b>
                </td>
                <td>{!!$hase_exhibition->exhibition_time_start!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>exhibition_time_end : </i></b>
                </td>
                <td>{!!$hase_exhibition->exhibition_time_end!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection