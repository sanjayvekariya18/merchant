@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show exchange_asset_pair
    </h1>
    <br>
    <form method = 'get' action = '{!!url("exchange_asset_pair")!!}'>
        <button class = 'btn btn-primary'>exchange_asset_pair Index</button>
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
                    <b><i>pairs_id : </i></b>
                </td>
                <td>{!!$exchange_asset_pair->pairs_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>exchange_id : </i></b>
                </td>
                <td>{!!$exchange_asset_pair->exchange_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_from_id : </i></b>
                </td>
                <td>{!!$exchange_asset_pair->asset_from_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_into_id : </i></b>
                </td>
                <td>{!!$exchange_asset_pair->asset_into_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>priority : </i></b>
                </td>
                <td>{!!$exchange_asset_pair->priority!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>enable : </i></b>
                </td>
                <td>{!!$exchange_asset_pair->enable!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection