@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show hase_import
    </h1>
    <br>
    <form method = 'get' action = '{!!url("hase_import")!!}'>
        <button class = 'btn btn-primary'>hase_import Index</button>
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
                    <b><i>import_id : </i></b>
                </td>
                <td>{!!$hase_import->import_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>merchant_id : </i></b>
                </td>
                <td>{!!$hase_import->merchant_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>staff_id : </i></b>
                </td>
                <td>{!!$hase_import->staff_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>filename : </i></b>
                </td>
                <td>{!!$hase_import->filename!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>slug : </i></b>
                </td>
                <td>{!!$hase_import->slug!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>status_id : </i></b>
                </td>
                <td>{!!$hase_import->status_id!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection