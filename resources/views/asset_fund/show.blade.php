@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show asset_fund
    </h1>
    <br>
    <form method = 'get' action = '{!!url("asset_fund")!!}'>
        <button class = 'btn btn-primary'>asset_fund Index</button>
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
                    <b><i>fund_id : </i></b>
                </td>
                <td>{!!$asset_fund->fund_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>merchant_id : </i></b>
                </td>
                <td>{!!$asset_fund->merchant_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>staff_group_id : </i></b>
                </td>
                <td>{!!$asset_fund->staff_group_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>staff_id : </i></b>
                </td>
                <td>{!!$asset_fund->staff_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>account_id : </i></b>
                </td>
                <td>{!!$asset_fund->account_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_id : </i></b>
                </td>
                <td>{!!$asset_fund->asset_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_price : </i></b>
                </td>
                <td>{!!$asset_fund->asset_price!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_quantity : </i></b>
                </td>
                <td>{!!$asset_fund->asset_quantity!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>fund_timezone : </i></b>
                </td>
                <td>{!!$asset_fund->fund_timezone!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>fund_date : </i></b>
                </td>
                <td>{!!$asset_fund->fund_date!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>fund_time : </i></b>
                </td>
                <td>{!!$asset_fund->fund_time!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>status : </i></b>
                </td>
                <td>{!!$asset_fund->status!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection