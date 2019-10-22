@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show transactions_ledger
    </h1>
    <br>
    <form method = 'get' action = '{!!url("transactions_ledger")!!}'>
        <button class = 'btn btn-primary'>transactions_ledger Index</button>
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
                    <b><i>transaction_ledger_id : </i></b>
                </td>
                <td>{!!$transactions_ledger->transaction_ledger_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>trader_id : </i></b>
                </td>
                <td>{!!$transactions_ledger->trader_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>client_id : </i></b>
                </td>
                <td>{!!$transactions_ledger->client_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>exchange_id : </i></b>
                </td>
                <td>{!!$transactions_ledger->exchange_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>trade_timezone : </i></b>
                </td>
                <td>{!!$transactions_ledger->trade_timezone!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>trade_date : </i></b>
                </td>
                <td>{!!$transactions_ledger->trade_date!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>trade_time : </i></b>
                </td>
                <td>{!!$transactions_ledger->trade_time!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>side_type_id : </i></b>
                </td>
                <td>{!!$transactions_ledger->side_type_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_trade_id : </i></b>
                </td>
                <td>{!!$transactions_ledger->asset_trade_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_base_id : </i></b>
                </td>
                <td>{!!$transactions_ledger->asset_base_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>quantity_executed : </i></b>
                </td>
                <td>{!!$transactions_ledger->quantity_executed!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>quantity_balance : </i></b>
                </td>
                <td>{!!$transactions_ledger->quantity_balance!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>quantity_asset_id : </i></b>
                </td>
                <td>{!!$transactions_ledger->quantity_asset_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>trade_order_type_id : </i></b>
                </td>
                <td>{!!$transactions_ledger->trade_order_type_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>price : </i></b>
                </td>
                <td>{!!$transactions_ledger->price!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>trade_status_type_id : </i></b>
                </td>
                <td>{!!$transactions_ledger->trade_status_type_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>trade_reason_type_id : </i></b>
                </td>
                <td>{!!$transactions_ledger->trade_reason_type_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>fee_asset : </i></b>
                </td>
                <td>{!!$transactions_ledger->fee_asset!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>transaction_address : </i></b>
                </td>
                <td>{!!$transactions_ledger->transaction_address!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>transaction_address_url : </i></b>
                </td>
                <td>{!!$transactions_ledger->transaction_address_url!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>trade_transaction_type_id : </i></b>
                </td>
                <td>{!!$transactions_ledger->trade_transaction_type_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>transaction_exchange : </i></b>
                </td>
                <td>{!!$transactions_ledger->transaction_exchange!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>transaction_internal : </i></b>
                </td>
                <td>{!!$transactions_ledger->transaction_internal!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection