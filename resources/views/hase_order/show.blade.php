@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show hase_order
    </h1>
    <br>
    <form method = 'get' action = '{!!url("hase_order")!!}'>
        <button class = 'btn btn-primary'>hase_order Index</button>
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
                    <b><i>order_id : </i></b>
                </td>
                <td>{!!$hase_order->order_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>merchant_id : </i></b>
                </td>
                <td>{!!$hase_order->merchant_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>customer_id : </i></b>
                </td>
                <td>{!!$hase_order->customer_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>first_name : </i></b>
                </td>
                <td>{!!$hase_order->first_name!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>last_name : </i></b>
                </td>
                <td>{!!$hase_order->last_name!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>email : </i></b>
                </td>
                <td>{!!$hase_order->email!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>telephone : </i></b>
                </td>
                <td>{!!$hase_order->telephone!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>location_id : </i></b>
                </td>
                <td>{!!$hase_order->location_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>address_id : </i></b>
                </td>
                <td>{!!$hase_order->address_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>cart : </i></b>
                </td>
                <td>{!!$hase_order->cart!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>total_items : </i></b>
                </td>
                <td>{!!$hase_order->total_items!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>comment : </i></b>
                </td>
                <td>{!!$hase_order->comment!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>payment : </i></b>
                </td>
                <td>{!!$hase_order->payment!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>order_type : </i></b>
                </td>
                <td>{!!$hase_order->order_type!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>date_added : </i></b>
                </td>
                <td>{!!$hase_order->date_added!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>date_modified : </i></b>
                </td>
                <td>{!!$hase_order->date_modified!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>order_time : </i></b>
                </td>
                <td>{!!$hase_order->order_time!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>order_date : </i></b>
                </td>
                <td>{!!$hase_order->order_date!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>order_total : </i></b>
                </td>
                <td>{!!$hase_order->order_total!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>status_id : </i></b>
                </td>
                <td>{!!$hase_order->status_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>ip_address : </i></b>
                </td>
                <td>{!!$hase_order->ip_address!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>user_agent : </i></b>
                </td>
                <td>{!!$hase_order->user_agent!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>notify : </i></b>
                </td>
                <td>{!!$hase_order->notify!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>assignee_id : </i></b>
                </td>
                <td>{!!$hase_order->assignee_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>invoice_no : </i></b>
                </td>
                <td>{!!$hase_order->invoice_no!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>invoice_prefix : </i></b>
                </td>
                <td>{!!$hase_order->invoice_prefix!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>invoice_date : </i></b>
                </td>
                <td>{!!$hase_order->invoice_date!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection