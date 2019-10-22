@extends('scaffold-interface.layouts.defaultMaterialize')
@section('title','Show')
@section('content')

<div class = 'container'>
    <h1>
        Show hase_translation_manage
    </h1>
    <form method = 'get' action = '{!!url("hase_translation_manage")!!}'>
        <button class = 'btn blue'>hase_translation_manage Index</button>
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
                <td>{!!$hase_translation_manage->manage_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>manage_table : </i></b>
                </td>
                <td>{!!$hase_translation_manage->manage_table!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>manage_table_id : </i></b>
                </td>
                <td>{!!$hase_translation_manage->manage_table_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>language_source : </i></b>
                </td>
                <td>{!!$hase_translation_manage->language_source!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>language_target : </i></b>
                </td>
                <td>{!!$hase_translation_manage->language_target!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>language_status : </i></b>
                </td>
                <td>{!!$hase_translation_manage->language_status!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>translator_user_id : </i></b>
                </td>
                <td>{!!$hase_translation_manage->translator_user_id !!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>approval_user_id : </i></b>
                </td>
                <td>{!!$hase_translation_manage->approval_user_id!!}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection