@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show transaction_summary
    </h1>
    <br>
    <form method = 'get' action = '{!!url("transaction_summary")!!}'>
        <button class = 'btn btn-primary'>transaction_summary Index</button>
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
                    <b><i>transaction_summary_id : </i></b>
                </td>
                <td>{!!$transaction_summary->transaction_summary_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>trader_id : </i></b>
                </td>
                <td>{!!$transaction_summary->trader_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>account_id : </i></b>
                </td>
                <td>{!!$transaction_summary->account_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>exchange_id : </i></b>
                </td>
                <td>{!!$transaction_summary->exchange_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>trade_timezone : </i></b>
                </td>
                <td>{!!$transaction_summary->trade_timezone!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>trade_date : </i></b>
                </td>
                <td>{!!$transaction_summary->trade_date!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>trade_time : </i></b>
                </td>
                <td>{!!$transaction_summary->trade_time!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>side_type_id : </i></b>
                </td>
                <td>{!!$transaction_summary->side_type_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_trade_id : </i></b>
                </td>
                <td>{!!$transaction_summary->asset_trade_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_base_id : </i></b>
                </td>
                <td>{!!$transaction_summary->asset_base_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>quantity_executed : </i></b>
                </td>
                <td>{!!$transaction_summary->quantity_executed!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>quantity_asset_id : </i></b>
                </td>
                <td>{!!$transaction_summary->quantity_asset_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>trade_order_type_id : </i></b>
                </td>
                <td>{!!$transaction_summary->trade_order_type_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>price : </i></b>
                </td>
                <td>{!!$transaction_summary->price!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>trade_status_type_id : </i></b>
                </td>
                <td>{!!$transaction_summary->trade_status_type_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>trade_reason_type_id : </i></b>
                </td>
                <td>{!!$transaction_summary->trade_reason_type_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>trade_transaction_type_id : </i></b>
                </td>
                <td>{!!$transaction_summary->trade_transaction_type_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>transaction_fee : </i></b>
                </td>
                <td>{!!$transaction_summary->transaction_fee!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>transaction_fee_asset : </i></b>
                </td>
                <td>{!!$transaction_summary->transaction_fee_asset!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>transaction_exchange : </i></b>
                </td>
                <td>{!!$transaction_summary->transaction_exchange!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>transaction_internal : </i></b>
                </td>
                <td>{!!$transaction_summary->transaction_internal!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection