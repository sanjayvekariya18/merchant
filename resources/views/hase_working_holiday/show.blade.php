@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show hase_working_holiday
    </h1>
    <br>
    <form method = 'get' action = '{!!url("hase_working_holiday")!!}'>
        <button class = 'btn btn-primary'>hase_working_holiday Index</button>
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
                    <b><i>holiday_id : </i></b>
                </td>
                <td>{!!$hase_working_holiday->holiday_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>holiday_country_id : </i></b>
                </td>
                <td>{!!$hase_working_holiday->holiday_country_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>holiday_state_id : </i></b>
                </td>
                <td>{!!$hase_working_holiday->holiday_state_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>holiday_date : </i></b>
                </td>
                <td>{!!$hase_working_holiday->holiday_date!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>holiday_name : </i></b>
                </td>
                <td>{!!$hase_working_holiday->holiday_name!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection