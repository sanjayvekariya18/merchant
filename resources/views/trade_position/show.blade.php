@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show position
    </h1>
    <br>
    <form method = 'get' action = '{!!url("position")!!}'>
        <button class = 'btn btn-primary'>position Index</button>
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
                    <b><i>position_id : </i></b>
                </td>
                <td>{!!$position->position_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>trader_id : </i></b>
                </td>
                <td>{!!$position->trader_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>account_id : </i></b>
                </td>
                <td>{!!$position->account_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>exchange_id : </i></b>
                </td>
                <td>{!!$position->exchange_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>side_type_id : </i></b>
                </td>
                <td>{!!$position->side_type_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_trade_id : </i></b>
                </td>
                <td>{!!$position->asset_trade_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_base_id : </i></b>
                </td>
                <td>{!!$position->asset_base_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>quantity_size : </i></b>
                </td>
                <td>{!!$position->quantity_size!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>quantity_remaining : </i></b>
                </td>
                <td>{!!$position->quantity_remaining!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>quantity_asset_id : </i></b>
                </td>
                <td>{!!$position->quantity_asset_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>trade_order_type_id : </i></b>
                </td>
                <td>{!!$position->trade_order_type_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>price_average : </i></b>
                </td>
                <td>{!!$position->price_average!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>leverage : </i></b>
                </td>
                <td>{!!$position->leverage!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>fee_asset : </i></b>
                </td>
                <td>{!!$position->fee_asset!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>pnl : </i></b>
                </td>
                <td>{!!$position->pnl!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>pnl_percent : </i></b>
                </td>
                <td>{!!$position->pnl_percent!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection