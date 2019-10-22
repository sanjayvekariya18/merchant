@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show social_apikey
    </h1>
    <br>
    <form method = 'get' action = '{!!url("social_apikey")!!}'>
        <button class = 'btn btn-primary'>social_apikey Index</button>
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
                    <b><i>social_id : </i></b>
                </td>
                <td>{!!$social_apikey->social_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>identity_id : </i></b>
                </td>
                <td>{!!$social_apikey->identity_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>identity_table_id : </i></b>
                </td>
                <td>{!!$social_apikey->identity_table_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>connector_id : </i></b>
                </td>
                <td>{!!$social_apikey->connector_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>connector_key : </i></b>
                </td>
                <td>{!!$social_apikey->connector_key!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>connector_passcode : </i></b>
                </td>
                <td>{!!$social_apikey->connector_passcode!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection