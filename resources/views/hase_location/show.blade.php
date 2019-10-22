@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Show Location
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    
    <!--end of page level css-->
@stop
@section('content')

<section class="content">
    <section class="content-header">
        <h1>Show Location</h1>
        <ol class="breadcrumb">
            <li>
                <a href="index ">
                    <i class="fa fa-fw fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="#"> Restaurant</a>
            </li>
            <li class="active">
                Show Location
            </li>
        </ol>
    </section>
    <br>
    <form method = 'get' action = '{!!url("hase_location")!!}'>
        <button class = 'btn btn-primary'>hase_location Index</button>
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
                    <b><i>location_id : </i></b>
                </td>
                <td>{!!$hase_location->location_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>merchant_id : </i></b>
                </td>
                <td>{!!$hase_location->merchant_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>location_name : </i></b>
                </td>
                <td>{!!$hase_location->location_name!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>location_email : </i></b>
                </td>
                <td>{!!$hase_location->location_email!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>description : </i></b>
                </td>
                <td>{!!$hase_location->description!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>location_address_1 : </i></b>
                </td>
                <td>{!!$hase_location->location_address_1!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>location_address_2 : </i></b>
                </td>
                <td>{!!$hase_location->location_address_2!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>location_city : </i></b>
                </td>
                <td>{!!$hase_location->location_city!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>location_state : </i></b>
                </td>
                <td>{!!$hase_location->location_state!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>location_postcode : </i></b>
                </td>
                <td>{!!$hase_location->location_postcode!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>location_country_id : </i></b>
                </td>
                <td>{!!$hase_location->location_country_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>location_telephone : </i></b>
                </td>
                <td>{!!$hase_location->location_telephone!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>location_website : </i></b>
                </td>
                <td>{!!$hase_location->location_website!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>location_lat : </i></b>
                </td>
                <td>{!!$hase_location->location_lat!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>location_lng : </i></b>
                </td>
                <td>{!!$hase_location->location_lng!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>location_radius : </i></b>
                </td>
                <td>{!!$hase_location->location_radius!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>offer_delivery : </i></b>
                </td>
                <td>{!!$hase_location->offer_delivery!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>offer_collection : </i></b>
                </td>
                <td>{!!$hase_location->offer_collection!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>delivery_time : </i></b>
                </td>
                <td>{!!$hase_location->delivery_time!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>last_order_time : </i></b>
                </td>
                <td>{!!$hase_location->last_order_time!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>reservation_time_interval : </i></b>
                </td>
                <td>{!!$hase_location->reservation_time_interval!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>reservation_stay_time : </i></b>
                </td>
                <td>{!!$hase_location->reservation_stay_time!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>location_status : </i></b>
                </td>
                <td>{!!$hase_location->location_status!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>collection_time : </i></b>
                </td>
                <td>{!!$hase_location->collection_time!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>options : </i></b>
                </td>
                <td>{!!$hase_location->options!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>location_image : </i></b>
                </td>
                <td>{!!$hase_location->location_image!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection