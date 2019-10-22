@extends('scaffold-interface.layouts.app')
@section('title','Edit')
@section('content')

<section class="content">
    <h1>
        Edit tax_type
    </h1>
    <form method = 'get' action = '{!!url("tax_type")!!}'>
        <button class = 'btn btn-danger'>tax_type Index</button>
    </form>
    <br>
    <form method = 'POST' action = '{!! url("tax_type")!!}/{!!$tax_type->
        id!!}/update'> 
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <div class="form-group">
            <label for="type_id">type_id</label>
            <input id="type_id" name = "type_id" type="text" class="form-control" value="{!!$tax_type->
            type_id!!}"> 
        </div>
        <div class="form-group">
            <label for="type_code">type_code</label>
            <input id="type_code" name = "type_code" type="text" class="form-control" value="{!!$tax_type->
            type_code!!}"> 
        </div>
        <div class="form-group">
            <label for="type_name">type_name</label>
            <input id="type_name" name = "type_name" type="text" class="form-control" value="{!!$tax_type->
            type_name!!}"> 
        </div>
        <button class = 'btn btn-primary' type ='submit'>Update</button>
    </form>
</section>
@endsection