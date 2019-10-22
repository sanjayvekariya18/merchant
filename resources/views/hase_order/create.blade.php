@extends('scaffold-interface.layouts.app')
@section('title','Create')
@section('content')

<section class="content">
    <h1>
        Create hase_order
    </h1>
    <form method = 'get' action = '{!!url("hase_order")!!}'>
        <button class = 'btn btn-danger'>hase_order Index</button>
    </form>
    <br>
    <form method = 'POST' action = '{!!url("hase_order")!!}'>
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <div class="form-group">
            <label for="order_id">order_id</label>
            <input id="order_id" name = "order_id" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="merchant_id">merchant_id</label>
            <input id="merchant_id" name = "merchant_id" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="customer_id">customer_id</label>
            <input id="customer_id" name = "customer_id" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="first_name">first_name</label>
            <input id="first_name" name = "first_name" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="last_name">last_name</label>
            <input id="last_name" name = "last_name" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="email">email</label>
            <input id="email" name = "email" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="telephone">telephone</label>
            <input id="telephone" name = "telephone" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="location_id">location_id</label>
            <input id="location_id" name = "location_id" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="address_id">address_id</label>
            <input id="address_id" name = "address_id" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="cart">cart</label>
            <input id="cart" name = "cart" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="total_items">total_items</label>
            <input id="total_items" name = "total_items" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="comment">comment</label>
            <input id="comment" name = "comment" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="payment">payment</label>
            <input id="payment" name = "payment" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="order_type">order_type</label>
            <input id="order_type" name = "order_type" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="date_added">date_added</label>
            <input id="date_added" name = "date_added" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="date_modified">date_modified</label>
            <input id="date_modified" name = "date_modified" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="order_time">order_time</label>
            <input id="order_time" name = "order_time" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="order_date">order_date</label>
            <input id="order_date" name = "order_date" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="order_total">order_total</label>
            <input id="order_total" name = "order_total" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="status_id">status_id</label>
            <input id="status_id" name = "status_id" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="ip_address">ip_address</label>
            <input id="ip_address" name = "ip_address" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="user_agent">user_agent</label>
            <input id="user_agent" name = "user_agent" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="notify">notify</label>
            <input id="notify" name = "notify" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="assignee_id">assignee_id</label>
            <input id="assignee_id" name = "assignee_id" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="invoice_no">invoice_no</label>
            <input id="invoice_no" name = "invoice_no" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="invoice_prefix">invoice_prefix</label>
            <input id="invoice_prefix" name = "invoice_prefix" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="invoice_date">invoice_date</label>
            <input id="invoice_date" name = "invoice_date" type="text" class="form-control">
        </div>
        <button class = 'btn btn-primary' type ='submit'>Create</button>
    </form>
</section>
@endsection