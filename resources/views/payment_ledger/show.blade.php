@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show payment_ledger
    </h1>
    <br>
    <form method = 'get' action = '{!!url("payment_ledger")!!}'>
        <button class = 'btn btn-primary'>payment_ledger Index</button>
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
                    <b><i>ledger_id : </i></b>
                </td>
                <td>{!!$payment_ledger->ledger_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>summary_id : </i></b>
                </td>
                <td>{!!$payment_ledger->summary_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>location_city_id : </i></b>
                </td>
                <td>{!!$payment_ledger->location_city_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>group_id : </i></b>
                </td>
                <td>{!!$payment_ledger->group_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>staff_id : </i></b>
                </td>
                <td>{!!$payment_ledger->staff_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>merchant_account_id : </i></b>
                </td>
                <td>{!!$payment_ledger->merchant_account_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>customer_account_id : </i></b>
                </td>
                <td>{!!$payment_ledger->customer_account_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>vendor_name : </i></b>
                </td>
                <td>{!!$payment_ledger->vendor_name!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>payment_type : </i></b>
                </td>
                <td>{!!$payment_ledger->payment_type!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>payment_description : </i></b>
                </td>
                <td>{!!$payment_ledger->payment_description!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>payment_name : </i></b>
                </td>
                <td>{!!$payment_ledger->payment_name!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>payment_timezone : </i></b>
                </td>
                <td>{!!$payment_ledger->payment_timezone!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>payment_date : </i></b>
                </td>
                <td>{!!$payment_ledger->payment_date!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>payment_time : </i></b>
                </td>
                <td>{!!$payment_ledger->payment_time!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>payment_notes : </i></b>
                </td>
                <td>{!!$payment_ledger->payment_notes!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>payment_quantity : </i></b>
                </td>
                <td>{!!$payment_ledger->payment_quantity!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>payment_unit_price : </i></b>
                </td>
                <td>{!!$payment_ledger->payment_unit_price!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>payment_amount : </i></b>
                </td>
                <td>{!!$payment_ledger->payment_amount!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>payment_asset_id : </i></b>
                </td>
                <td>{!!$payment_ledger->payment_asset_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>payment_fee_id : </i></b>
                </td>
                <td>{!!$payment_ledger->payment_fee_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>payment_status : </i></b>
                </td>
                <td>{!!$payment_ledger->payment_status!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>fee_summary_id : </i></b>
                </td>
                <td>{!!$payment_ledger->fee_summary_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>transaction_type_id : </i></b>
                </td>
                <td>{!!$payment_ledger->transaction_type_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>transaction_address : </i></b>
                </td>
                <td>{!!$payment_ledger->transaction_address!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>transaction_address_url : </i></b>
                </td>
                <td>{!!$payment_ledger->transaction_address_url!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>transaction_photo_url : </i></b>
                </td>
                <td>{!!$payment_ledger->transaction_photo_url!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>transaction_internal_ref : </i></b>
                </td>
                <td>{!!$payment_ledger->transaction_internal_ref!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>transaction_root : </i></b>
                </td>
                <td>{!!$payment_ledger->transaction_root!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>ledger_hash : </i></b>
                </td>
                <td>{!!$payment_ledger->ledger_hash!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection