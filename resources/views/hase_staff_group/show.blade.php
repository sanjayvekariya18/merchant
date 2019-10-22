@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show hase_staff_group
    </h1>
    <br>
    <form method = 'get' action = '{!!url("hase_staff_group")!!}'>
        <button class = 'btn btn-primary'>hase_staff_group Index</button>
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
                    <b><i>staff_group_id : </i></b>
                </td>
                <td>{!!$hase_staff_group->staff_group_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>staff_group_name : </i></b>
                </td>
                <td>{!!$hase_staff_group->staff_group_name!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>customer_account_access : </i></b>
                </td>
                <td>{!!$hase_staff_group->customer_account_access!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>location_access : </i></b>
                </td>
                <td>{!!$hase_staff_group->location_access!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>permissions : </i></b>
                </td>
                <td>{!!$hase_staff_group->permissions!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection