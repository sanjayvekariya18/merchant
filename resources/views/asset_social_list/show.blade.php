@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show asset_social_list
    </h1>
    <br>
    <form method = 'get' action = '{!!url("asset_social_list")!!}'>
        <button class = 'btn btn-primary'>asset_social_list Index</button>
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
                <td>{!!$asset_social_list->list_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_id : </i></b>
                </td>
                <td>{!!$asset_social_list->asset_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>social_id : </i></b>
                </td>
                <td>{!!$asset_social_list->social_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>social_url : </i></b>
                </td>
                <td>{!!$asset_social_list->social_url!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>priority : </i></b>
                </td>
                <td>{!!$asset_social_list->priority!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection