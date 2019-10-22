@extends('scaffold-interface.layouts.app')
@section('title','Create')
@section('content')

<section class="content">
    <h1>
        Create tax_type
    </h1>
    <form method = 'get' action = '{!!url("tax_type")!!}'>
        <button class = 'btn btn-danger'>tax_type Index</button>
    </form>
    <br>
    <form method = 'POST' action = '{!!url("tax_type")!!}'>
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <div class="form-group">
            <label for="type_id">type_id</label>
            <input id="type_id" name = "type_id" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="type_code">type_code</label>
            <input id="type_code" name = "type_code" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="type_name">type_name</label>
            <input id="type_name" name = "type_name" type="text" class="form-control">
        </div>
        <button class = 'btn btn-primary' type ='submit'>Create</button>
    </form>
</section>
@endsection