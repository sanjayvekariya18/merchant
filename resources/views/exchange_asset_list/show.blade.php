@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show exchange_asset_list
    </h1>
    <br>
    <form method = 'get' action = '{!!url("exchange_asset_list")!!}'>
        <button class = 'btn btn-primary'>exchange_asset_list Index</button>
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
                <td>{!!$exchange_asset_list->list_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>exchange_id : </i></b>
                </td>
                <td>{!!$exchange_asset_list->exchange_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_id : </i></b>
                </td>
                <td>{!!$exchange_asset_list->asset_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_code : </i></b>
                </td>
                <td>{!!$exchange_asset_list->asset_code!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>priority : </i></b>
                </td>
                <td>{!!$exchange_asset_list->priority!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>enable : </i></b>
                </td>
                <td>{!!$exchange_asset_list->enable!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection