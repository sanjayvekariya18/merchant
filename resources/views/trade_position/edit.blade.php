@extends('scaffold-interface.layouts.app')
@section('title','Edit')
@section('content')

<section class="content">
    <h1>
        Edit position
    </h1>
    <form method = 'get' action = '{!!url("position")!!}'>
        <button class = 'btn btn-danger'>position Index</button>
    </form>
    <br>
    <form method = 'POST' action = '{!! url("position")!!}/{!!$position->
        id!!}/update'> 
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <div class="form-group">
            <label for="position_id">position_id</label>
            <input id="position_id" name = "position_id" type="text" class="form-control" value="{!!$position->
            position_id!!}"> 
        </div>
        <div class="form-group">
            <label for="trader_id">trader_id</label>
            <input id="trader_id" name = "trader_id" type="text" class="form-control" value="{!!$position->
            trader_id!!}"> 
        </div>
        <div class="form-group">
            <label for="account_id">account_id</label>
            <input id="account_id" name = "account_id" type="text" class="form-control" value="{!!$position->
            account_id!!}"> 
        </div>
        <div class="form-group">
            <label for="exchange_id">exchange_id</label>
            <input id="exchange_id" name = "exchange_id" type="text" class="form-control" value="{!!$position->
            exchange_id!!}"> 
        </div>
        <div class="form-group">
            <label for="side_type_id">side_type_id</label>
            <input id="side_type_id" name = "side_type_id" type="text" class="form-control" value="{!!$position->
            side_type_id!!}"> 
        </div>
        <div class="form-group">
            <label for="asset_trade_id">asset_trade_id</label>
            <input id="asset_trade_id" name = "asset_trade_id" type="text" class="form-control" value="{!!$position->
            asset_trade_id!!}"> 
        </div>
        <div class="form-group">
            <label for="asset_base_id">asset_base_id</label>
            <input id="asset_base_id" name = "asset_base_id" type="text" class="form-control" value="{!!$position->
            asset_base_id!!}"> 
        </div>
        <div class="form-group">
            <label for="quantity_size">quantity_size</label>
            <input id="quantity_size" name = "quantity_size" type="text" class="form-control" value="{!!$position->
            quantity_size!!}"> 
        </div>
        <div class="form-group">
            <label for="quantity_remaining">quantity_remaining</label>
            <input id="quantity_remaining" name = "quantity_remaining" type="text" class="form-control" value="{!!$position->
            quantity_remaining!!}"> 
        </div>
        <div class="form-group">
            <label for="quantity_asset_id">quantity_asset_id</label>
            <input id="quantity_asset_id" name = "quantity_asset_id" type="text" class="form-control" value="{!!$position->
            quantity_asset_id!!}"> 
        </div>
        <div class="form-group">
            <label for="trade_order_type_id">trade_order_type_id</label>
            <input id="trade_order_type_id" name = "trade_order_type_id" type="text" class="form-control" value="{!!$position->
            trade_order_type_id!!}"> 
        </div>
        <div class="form-group">
            <label for="price_average">price_average</label>
            <input id="price_average" name = "price_average" type="text" class="form-control" value="{!!$position->
            price_average!!}"> 
        </div>
        <div class="form-group">
            <label for="leverage">leverage</label>
            <input id="leverage" name = "leverage" type="text" class="form-control" value="{!!$position->
            leverage!!}"> 
        </div>
        <div class="form-group">
            <label for="fee_asset">fee_asset</label>
            <input id="fee_asset" name = "fee_asset" type="text" class="form-control" value="{!!$position->
            fee_asset!!}"> 
        </div>
        <div class="form-group">
            <label for="pnl">pnl</label>
            <input id="pnl" name = "pnl" type="text" class="form-control" value="{!!$position->
            pnl!!}"> 
        </div>
        <div class="form-group">
            <label for="pnl_percent">pnl_percent</label>
            <input id="pnl_percent" name = "pnl_percent" type="text" class="form-control" value="{!!$position->
            pnl_percent!!}"> 
        </div>
        <button class = 'btn btn-primary' type ='submit'>Update</button>
    </form>
</section>
@endsection