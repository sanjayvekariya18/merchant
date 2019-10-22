@extends('scaffold-interface.layouts.app')
@section('title','Edit')
@section('content')

<section class="content">
    <h1>
        Edit payment_ledger
    </h1>
    <form method = 'get' action = '{!!url("payment_ledger")!!}'>
        <button class = 'btn btn-danger'>payment_ledger Index</button>
    </form>
    <br>
    <form method = 'POST' action = '{!! url("payment_ledger")!!}/{!!$payment_ledger->
        id!!}/update'> 
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <div class="form-group">
            <label for="ledger_id">ledger_id</label>
            <input id="ledger_id" name = "ledger_id" type="text" class="form-control" value="{!!$payment_ledger->
            ledger_id!!}"> 
        </div>
        <div class="form-group">
            <label for="summary_id">summary_id</label>
            <input id="summary_id" name = "summary_id" type="text" class="form-control" value="{!!$payment_ledger->
            summary_id!!}"> 
        </div>
        <div class="form-group">
            <label for="location_city_id">location_city_id</label>
            <input id="location_city_id" name = "location_city_id" type="text" class="form-control" value="{!!$payment_ledger->
            location_city_id!!}"> 
        </div>
        <div class="form-group">
            <label for="group_id">group_id</label>
            <input id="group_id" name = "group_id" type="text" class="form-control" value="{!!$payment_ledger->
            group_id!!}"> 
        </div>
        <div class="form-group">
            <label for="staff_id">staff_id</label>
            <input id="staff_id" name = "staff_id" type="text" class="form-control" value="{!!$payment_ledger->
            staff_id!!}"> 
        </div>
        <div class="form-group">
            <label for="merchant_account_id">merchant_account_id</label>
            <input id="merchant_account_id" name = "merchant_account_id" type="text" class="form-control" value="{!!$payment_ledger->
            merchant_account_id!!}"> 
        </div>
        <div class="form-group">
            <label for="customer_account_id">customer_account_id</label>
            <input id="customer_account_id" name = "customer_account_id" type="text" class="form-control" value="{!!$payment_ledger->
            customer_account_id!!}"> 
        </div>
        <div class="form-group">
            <label for="vendor_name">vendor_name</label>
            <input id="vendor_name" name = "vendor_name" type="text" class="form-control" value="{!!$payment_ledger->
            vendor_name!!}"> 
        </div>
        <div class="form-group">
            <label for="payment_type">payment_type</label>
            <input id="payment_type" name = "payment_type" type="text" class="form-control" value="{!!$payment_ledger->
            payment_type!!}"> 
        </div>
        <div class="form-group">
            <label for="payment_description">payment_description</label>
            <input id="payment_description" name = "payment_description" type="text" class="form-control" value="{!!$payment_ledger->
            payment_description!!}"> 
        </div>
        <div class="form-group">
            <label for="payment_name">payment_name</label>
            <input id="payment_name" name = "payment_name" type="text" class="form-control" value="{!!$payment_ledger->
            payment_name!!}"> 
        </div>
        <div class="form-group">
            <label for="payment_timezone">payment_timezone</label>
            <input id="payment_timezone" name = "payment_timezone" type="text" class="form-control" value="{!!$payment_ledger->
            payment_timezone!!}"> 
        </div>
        <div class="form-group">
            <label for="payment_date">payment_date</label>
            <input id="payment_date" name = "payment_date" type="text" class="form-control" value="{!!$payment_ledger->
            payment_date!!}"> 
        </div>
        <div class="form-group">
            <label for="payment_time">payment_time</label>
            <input id="payment_time" name = "payment_time" type="text" class="form-control" value="{!!$payment_ledger->
            payment_time!!}"> 
        </div>
        <div class="form-group">
            <label for="payment_notes">payment_notes</label>
            <input id="payment_notes" name = "payment_notes" type="text" class="form-control" value="{!!$payment_ledger->
            payment_notes!!}"> 
        </div>
        <div class="form-group">
            <label for="payment_quantity">payment_quantity</label>
            <input id="payment_quantity" name = "payment_quantity" type="text" class="form-control" value="{!!$payment_ledger->
            payment_quantity!!}"> 
        </div>
        <div class="form-group">
            <label for="payment_unit_price">payment_unit_price</label>
            <input id="payment_unit_price" name = "payment_unit_price" type="text" class="form-control" value="{!!$payment_ledger->
            payment_unit_price!!}"> 
        </div>
        <div class="form-group">
            <label for="payment_amount">payment_amount</label>
            <input id="payment_amount" name = "payment_amount" type="text" class="form-control" value="{!!$payment_ledger->
            payment_amount!!}"> 
        </div>
        <div class="form-group">
            <label for="payment_asset_id">payment_asset_id</label>
            <input id="payment_asset_id" name = "payment_asset_id" type="text" class="form-control" value="{!!$payment_ledger->
            payment_asset_id!!}"> 
        </div>
        <div class="form-group">
            <label for="payment_fee_id">payment_fee_id</label>
            <input id="payment_fee_id" name = "payment_fee_id" type="text" class="form-control" value="{!!$payment_ledger->
            payment_fee_id!!}"> 
        </div>
        <div class="form-group">
            <label for="payment_status">payment_status</label>
            <input id="payment_status" name = "payment_status" type="text" class="form-control" value="{!!$payment_ledger->
            payment_status!!}"> 
        </div>
        <div class="form-group">
            <label for="fee_summary_id">fee_summary_id</label>
            <input id="fee_summary_id" name = "fee_summary_id" type="text" class="form-control" value="{!!$payment_ledger->
            fee_summary_id!!}"> 
        </div>
        <div class="form-group">
            <label for="transaction_type_id">transaction_type_id</label>
            <input id="transaction_type_id" name = "transaction_type_id" type="text" class="form-control" value="{!!$payment_ledger->
            transaction_type_id!!}"> 
        </div>
        <div class="form-group">
            <label for="transaction_address">transaction_address</label>
            <input id="transaction_address" name = "transaction_address" type="text" class="form-control" value="{!!$payment_ledger->
            transaction_address!!}"> 
        </div>
        <div class="form-group">
            <label for="transaction_address_url">transaction_address_url</label>
            <input id="transaction_address_url" name = "transaction_address_url" type="text" class="form-control" value="{!!$payment_ledger->
            transaction_address_url!!}"> 
        </div>
        <div class="form-group">
            <label for="transaction_photo_url">transaction_photo_url</label>
            <input id="transaction_photo_url" name = "transaction_photo_url" type="text" class="form-control" value="{!!$payment_ledger->
            transaction_photo_url!!}"> 
        </div>
        <div class="form-group">
            <label for="transaction_internal_ref">transaction_internal_ref</label>
            <input id="transaction_internal_ref" name = "transaction_internal_ref" type="text" class="form-control" value="{!!$payment_ledger->
            transaction_internal_ref!!}"> 
        </div>
        <div class="form-group">
            <label for="transaction_root">transaction_root</label>
            <input id="transaction_root" name = "transaction_root" type="text" class="form-control" value="{!!$payment_ledger->
            transaction_root!!}"> 
        </div>
        <div class="form-group">
            <label for="ledger_hash">ledger_hash</label>
            <input id="ledger_hash" name = "ledger_hash" type="text" class="form-control" value="{!!$payment_ledger->
            ledger_hash!!}"> 
        </div>
        <button class = 'btn btn-primary' type ='submit'>Update</button>
    </form>
</section>
@endsection