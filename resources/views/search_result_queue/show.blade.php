@extends('scaffold-interface.layouts.defaultMaterialize')
@section('title','Show')
@section('content')

<div class = 'container'>
    <h1>
        Show search_result_queue
    </h1>
    <form method = 'get' action = '{!!url("search_result_queue")!!}'>
        <button class = 'btn blue'>search_result_queue Index</button>
    </form>
    <table class = 'highlight bordered'>
        <thead>
            <th>Key</th>
            <th>Value</th>
        </thead>
        <tbody>
            <tr>
                <td>
                    <b><i>id : </i></b>
                </td>
                <td>{!!$search_result_queue->id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>event_title : </i></b>
                </td>
                <td>{!!$search_result_queue->event_title!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>keyword_list_id : </i></b>
                </td>
                <td>{!!$search_result_queue->keyword_list_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>status_id : </i></b>
                </td>
                <td>{!!$search_result_queue->status_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>user_id : </i></b>
                </td>
                <td>{!!$search_result_queue->user_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>event_url : </i></b>
                </td>
                <td>{!!$search_result_queue->event_url!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>entry_date : </i></b>
                </td>
                <td>{!!$search_result_queue->entry_date!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>entry_time : </i></b>
                </td>
                <td>{!!$search_result_queue->entry_time!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>website_id : </i></b>
                </td>
                <td>{!!$search_result_queue->website_id!!}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection