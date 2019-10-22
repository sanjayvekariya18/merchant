@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show hase_approval_group_list
    </h1>
    <br>
    <form method = 'get' action = '{!!url("hase_approval_group_list")!!}'>
        <button class = 'btn btn-primary'>hase_approval_group_list Index</button>
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
                    <b><i>staff_group_list_id : </i></b>
                </td>
                <td>{!!$hase_approval_group_list->staff_group_list_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>source_group_id : </i></b>
                </td>
                <td>{!!$hase_approval_group_list->source_group_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>target_group_id : </i></b>
                </td>
                <td>{!!$hase_approval_group_list->target_group_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>status_source : </i></b>
                </td>
                <td>{!!$hase_approval_group_list->status_source!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>status_target : </i></b>
                </td>
                <td>{!!$hase_approval_group_list->status_target!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection