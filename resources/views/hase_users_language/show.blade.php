@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show users_language
    </h1>
    <br>
    <form method = 'get' action = '{!!url("users_language")!!}'>
        <button class = 'btn btn-primary'>users_language Index</button>
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
                    <b><i>id : </i></b>
                </td>
                <td>{!!$users_language->id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i> language_code : </i></b>
                </td>
                <td>{!!$users_language-> language_code!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>user_id : </i></b>
                </td>
                <td>{!!$users_language->user_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>language_priority : </i></b>
                </td>
                <td>{!!$users_language->language_priority!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>language_name : </i></b>
                </td>
                <td>{!!$users_language->language_name!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection