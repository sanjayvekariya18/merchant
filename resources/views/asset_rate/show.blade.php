@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show asset_rate
    </h1>
    <br>
    <form method = 'get' action = '{!!url("asset_rate")!!}'>
        <button class = 'btn btn-primary'>asset_rate Index</button>
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
                    <b><i>rate_id : </i></b>
                </td>
                <td>{!!$asset_rate->rate_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_from_id : </i></b>
                </td>
                <td>{!!$asset_rate->asset_from_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_into_id : </i></b>
                </td>
                <td>{!!$asset_rate->asset_into_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_buy_price : </i></b>
                </td>
                <td>{!!$asset_rate->asset_buy_price!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_ask_price : </i></b>
                </td>
                <td>{!!$asset_rate->asset_ask_price!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_last_price : </i></b>
                </td>
                <td>{!!$asset_rate->asset_last_price!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_last_date : </i></b>
                </td>
                <td>{!!$asset_rate->asset_last_date!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_last_time : </i></b>
                </td>
                <td>{!!$asset_rate->asset_last_time!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_last_timezone : </i></b>
                </td>
                <td>{!!$asset_rate->asset_last_timezone!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection