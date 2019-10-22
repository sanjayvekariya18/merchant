@extends('scaffold-interface.layouts.app')
@section('title','Index')
@section('content')

<section class="content">
    <h1>
        Payment_ledger Index
    </h1>
    <form class = 'col s3' method = 'get' action = '{!!url("payment_ledger")!!}/create'>
        <button class = 'btn btn-primary' type = 'submit'>Create New payment_ledger</button>
    </form>
    <br>
    <br>
    <table class = "table table-striped table-bordered table-hover" style = 'background:#fff'>
        <thead>
            <th>ledger_id</th>
            <th>summary_id</th>
            <th>location_city_id</th>
            <th>group_id</th>
            <th>staff_id</th>
            <th>merchant_account_id</th>
            <th>customer_account_id</th>
            <th>vendor_name</th>
            <th>payment_type</th>
            <th>payment_description</th>
            <th>payment_name</th>
            <th>payment_timezone</th>
            <th>payment_date</th>
            <th>payment_time</th>
            <th>payment_notes</th>
            <th>payment_quantity</th>
            <th>payment_unit_price</th>
            <th>payment_amount</th>
            <th>payment_asset_id</th>
            <th>payment_fee_id</th>
            <th>payment_status</th>
            <th>fee_summary_id</th>
            <th>transaction_type_id</th>
            <th>transaction_address</th>
            <th>transaction_address_url</th>
            <th>transaction_photo_url</th>
            <th>transaction_internal_ref</th>
            <th>transaction_root</th>
            <th>ledger_hash</th>
            <th>actions</th>
        </thead>
        <tbody>
            @foreach($payment_ledgers as $payment_ledger) 
            <tr>
                <td>{!!$payment_ledger->ledger_id!!}</td>
                <td>{!!$payment_ledger->summary_id!!}</td>
                <td>{!!$payment_ledger->location_city_id!!}</td>
                <td>{!!$payment_ledger->group_id!!}</td>
                <td>{!!$payment_ledger->staff_id!!}</td>
                <td>{!!$payment_ledger->merchant_account_id!!}</td>
                <td>{!!$payment_ledger->customer_account_id!!}</td>
                <td>{!!$payment_ledger->vendor_name!!}</td>
                <td>{!!$payment_ledger->payment_type!!}</td>
                <td>{!!$payment_ledger->payment_description!!}</td>
                <td>{!!$payment_ledger->payment_name!!}</td>
                <td>{!!$payment_ledger->payment_timezone!!}</td>
                <td>{!!$payment_ledger->payment_date!!}</td>
                <td>{!!$payment_ledger->payment_time!!}</td>
                <td>{!!$payment_ledger->payment_notes!!}</td>
                <td>{!!$payment_ledger->payment_quantity!!}</td>
                <td>{!!$payment_ledger->payment_unit_price!!}</td>
                <td>{!!$payment_ledger->payment_amount!!}</td>
                <td>{!!$payment_ledger->payment_asset_id!!}</td>
                <td>{!!$payment_ledger->payment_fee_id!!}</td>
                <td>{!!$payment_ledger->payment_status!!}</td>
                <td>{!!$payment_ledger->fee_summary_id!!}</td>
                <td>{!!$payment_ledger->transaction_type_id!!}</td>
                <td>{!!$payment_ledger->transaction_address!!}</td>
                <td>{!!$payment_ledger->transaction_address_url!!}</td>
                <td>{!!$payment_ledger->transaction_photo_url!!}</td>
                <td>{!!$payment_ledger->transaction_internal_ref!!}</td>
                <td>{!!$payment_ledger->transaction_root!!}</td>
                <td>{!!$payment_ledger->ledger_hash!!}</td>
                <td>
                    <a data-toggle="modal" data-target="#myModal" class = 'delete btn btn-danger btn-xs' data-link = "/payment_ledger/{!!$payment_ledger->id!!}/deleteMsg" ><i class = 'material-icons'>delete</i></a>
                    <a href = '#' class = 'viewEdit btn btn-primary btn-xs' data-link = '/payment_ledger/{!!$payment_ledger->id!!}/edit'><i class = 'material-icons'>edit</i></a>
                    <a href = '#' class = 'viewShow btn btn-warning btn-xs' data-link = '/payment_ledger/{!!$payment_ledger->id!!}'><i class = 'material-icons'>info</i></a>
                </td>
            </tr>
            @endforeach 
        </tbody>
    </table>
    {!! $payment_ledgers->render() !!}

</section>
@endsection