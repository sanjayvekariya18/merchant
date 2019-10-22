@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show asset_team_list
    </h1>
    <br>
    <form method = 'get' action = '{!!url("asset_team_list")!!}'>
        <button class = 'btn btn-primary'>asset_team_list Index</button>
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
                <td>{!!$asset_team_list->list_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_id : </i></b>
                </td>
                <td>{!!$asset_team_list->asset_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>team_id : </i></b>
                </td>
                <td>{!!$asset_team_list->team_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>member_id : </i></b>
                </td>
                <td>{!!$asset_team_list->member_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>priority : </i></b>
                </td>
                <td>{!!$asset_team_list->priority!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection