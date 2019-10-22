@extends('scaffold-interface.layouts.defaultMaterialize')
@section('title','Show')
@section('content')

<div class = 'container'>
    <h1>
        Show hase_translation_status_view_manage
    </h1>
    <form method = 'get' action = '{!!url("hase_status_view_manage")!!}'>
        <button class = 'btn blue'>hase_translation_status_view_manage Index</button>
    </form>
    <table class = 'highlight bordered'>
        <thead>
            <th>Key</th>
            <th>Value</th>
        </thead>
        <tbody>
            <tr>
                <td>
                    <b><i>manage_id : </i></b>
                </td>
                <td>{!!$hase_translation_status_view_manage->manage_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>status_target : </i></b>
                </td>
                <td>{!!$hase_translation_status_view_manage->status_target!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>user_view_status : </i></b>
                </td>
                <td>{!!$hase_translation_status_view_manage->user_view_status!!}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection