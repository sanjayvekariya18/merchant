@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show asset_flow
    </h1>
    <br>
    <form method = 'get' action = '{!!url("asset_flow")!!}'>
        <button class = 'btn btn-primary'>asset_flow Index</button>
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
                    <b><i>flow_id : </i></b>
                </td>
                <td>{!!$asset_flow->flow_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>merchant_id : </i></b>
                </td>
                <td>{!!$asset_flow->merchant_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>staff_group_id : </i></b>
                </td>
                <td>{!!$asset_flow->staff_group_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>staff_id : </i></b>
                </td>
                <td>{!!$asset_flow->staff_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_id : </i></b>
                </td>
                <td>{!!$asset_flow->asset_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_price_lower : </i></b>
                </td>
                <td>{!!$asset_flow->asset_price_lower!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_price_upper : </i></b>
                </td>
                <td>{!!$asset_flow->asset_price_upper!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_quantity_lower : </i></b>
                </td>
                <td>{!!$asset_flow->asset_quantity_lower!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_quantity_upper : </i></b>
                </td>
                <td>{!!$asset_flow->asset_quantity_upper!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_total_lower : </i></b>
                </td>
                <td>{!!$asset_flow->asset_total_lower!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_total_upper : </i></b>
                </td>
                <td>{!!$asset_flow->asset_total_upper!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection