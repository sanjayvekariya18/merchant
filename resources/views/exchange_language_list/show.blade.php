@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show exchange_language_list
    </h1>
    <br>
    <form method = 'get' action = '{!!url("exchange_language_list")!!}'>
        <button class = 'btn btn-primary'>exchange_language_list Index</button>
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
                    <b><i>list_id : </i></b>
                </td>
                <td>{!!$exchange_language_list->list_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>exchange_id : </i></b>
                </td>
                <td>{!!$exchange_language_list->exchange_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>language_id : </i></b>
                </td>
                <td>{!!$exchange_language_list->language_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>priority : </i></b>
                </td>
                <td>{!!$exchange_language_list->priority!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>enable : </i></b>
                </td>
                <td>{!!$exchange_language_list->enable!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection