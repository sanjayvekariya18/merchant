@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show hase_menu
    </h1>
    <br>
    <form method = 'get' action = '{!!url("hase_menu")!!}'>
        <button class = 'btn btn-primary'>hase_menu Index</button>
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
                    <b><i>menu_id : </i></b>
                </td>
                <td>{!!$hase_menu->menu_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>merchant_id : </i></b>
                </td>
                <td>{!!$hase_menu->merchant_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>location_id : </i></b>
                </td>
                <td>{!!$hase_menu->location_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>menu_name : </i></b>
                </td>
                <td>{!!$hase_menu->menu_name!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>menu_description : </i></b>
                </td>
                <td>{!!$hase_menu->menu_description!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>menu_price : </i></b>
                </td>
                <td>{!!$hase_menu->menu_price!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>menu_photo : </i></b>
                </td>
                <td>{!!$hase_menu->menu_photo!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>menu_category_id : </i></b>
                </td>
                <td>{!!$hase_menu->menu_category_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>stock_qty : </i></b>
                </td>
                <td>{!!$hase_menu->stock_qty!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>minimum_qty : </i></b>
                </td>
                <td>{!!$hase_menu->minimum_qty!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>subtract_stock : </i></b>
                </td>
                <td>{!!$hase_menu->subtract_stock!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>mealtime_id : </i></b>
                </td>
                <td>{!!$hase_menu->mealtime_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>menu_status : </i></b>
                </td>
                <td>{!!$hase_menu->menu_status!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>menu_priority : </i></b>
                </td>
                <td>{!!$hase_menu->menu_priority!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>menu_image : </i></b>
                </td>
                <td>{!!$hase_menu->menu_image!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection