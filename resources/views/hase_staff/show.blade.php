@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show hase_staff
    </h1>
    <br>
    <form method = 'get' action = '{!!url("hase_staff")!!}'>
        <button class = 'btn btn-primary'>hase_staff Index</button>
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
                    <b><i>staff_id : </i></b>
                </td>
                <td>{!!$hase_staff->staff_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>merchant_id : </i></b>
                </td>
                <td>{!!$hase_staff->merchant_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>staff_group_id : </i></b>
                </td>
                <td>{!!$hase_staff->staff_group_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>staff_location_id : </i></b>
                </td>
                <td>{!!$hase_staff->staff_location_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>language_id : </i></b>
                </td>
                <td>{!!$hase_staff->language_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>staff_name : </i></b>
                </td>
                <td>{!!$hase_staff->staff_name!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>staff_email : </i></b>
                </td>
                <td>{!!$hase_staff->staff_email!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>timezone : </i></b>
                </td>
                <td>{!!$hase_staff->timezone!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>date_added : </i></b>
                </td>
                <td>{!!$hase_staff->date_added!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>staff_status : </i></b>
                </td>
                <td>{!!$hase_staff->staff_status!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection