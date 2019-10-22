@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show hase_country
    </h1>
    <br>
    <form method = 'get' action = '{!!url("hase_country")!!}'>
        <button class = 'btn btn-primary'>hase_country Index</button>
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
                    <b><i>country_id : </i></b>
                </td>
                <td>{!!$hase_country->country_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>country_name : </i></b>
                </td>
                <td>{!!$hase_country->country_name!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>iso_code_2 : </i></b>
                </td>
                <td>{!!$hase_country->iso_code_2!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>iso_code_2 : </i></b>
                </td>
                <td>{!!$hase_country->iso_code_2!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>format : </i></b>
                </td>
                <td>{!!$hase_country->format!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>status : </i></b>
                </td>
                <td>{!!$hase_country->status!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>flag : </i></b>
                </td>
                <td>{!!$hase_country->flag!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>country_phone_code : </i></b>
                </td>
                <td>{!!$hase_country->country_phone_code!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>telephone_min : </i></b>
                </td>
                <td>{!!$hase_country->telephone_min!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>telephone_max : </i></b>
                </td>
                <td>{!!$hase_country->telephone_max!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection