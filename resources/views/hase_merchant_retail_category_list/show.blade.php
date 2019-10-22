@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show hase_merchant_retail_category_list
    </h1>
    <br>
    <form method = 'get' action = '{!!url("hase_merchant_retail_category_list")!!}'>
        <button class = 'btn btn-primary'>hase_merchant_retail_category_list Index</button>
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
                    <b><i>list_id : </i></b>
                </td>
                <td>{!!$hase_merchant_retail_category_list->list_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>merchant_id : </i></b>
                </td>
                <td>{!!$hase_merchant_retail_category_list->merchant_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>location_id : </i></b>
                </td>
                <td>{!!$hase_merchant_retail_category_list->location_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>category_type_id : </i></b>
                </td>
                <td>{!!$hase_merchant_retail_category_list->category_type_id!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection