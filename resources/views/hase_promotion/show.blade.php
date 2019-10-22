@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show hase_promotion
    </h1>
    <br>
    <form method = 'get' action = '{!!url("hase_promotion")!!}'>
        <button class = 'btn btn-primary'>hase_promotion Index</button>
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
                    <b><i>id : </i></b>
                </td>
                <td>{!!$hase_promotion->id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>merchant_id : </i></b>
                </td>
                <td>{!!$hase_promotion->merchant_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>image_url : </i></b>
                </td>
                <td>{!!$hase_promotion->image_url!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>merchant_name : </i></b>
                </td>
                <td>{!!$hase_promotion->merchant_name!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>offer_details : </i></b>
                </td>
                <td>{!!$hase_promotion->offer_details!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>offer_terms : </i></b>
                </td>
                <td>{!!$hase_promotion->offer_terms!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>priority : </i></b>
                </td>
                <td>{!!$hase_promotion->priority!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>offer_featured : </i></b>
                </td>
                <td>{!!$hase_promotion->offer_featured!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>offer_hottest : </i></b>
                </td>
                <td>{!!$hase_promotion->offer_hottest!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>offer_begin : </i></b>
                </td>
                <td>{!!$hase_promotion->offer_begin!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>offer_expire : </i></b>
                </td>
                <td>{!!$hase_promotion->offer_expire!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection