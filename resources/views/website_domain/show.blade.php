@extends('scaffold-interface.layouts.defaultMaterialize')
@section('title','Show')
@section('content')

<div class = 'container'>
    <h1>
        Show website_domain
    </h1>
    <form method = 'get' action = '{!!url("website_domain")!!}'>
        <button class = 'btn blue'>website_domain Index</button>
    </form>
    <table class = 'highlight bordered'>
        <thead>
            <th>Key</th>
            <th>Value</th>
        </thead>
        <tbody>
            <tr>
                <td>
                    <b><i> website_domain_id : </i></b>
                </td>
                <td>{!!$website_domain-> website_domain_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i> website_url : </i></b>
                </td>
                <td>{!!$website_domain-> website_url!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>status : </i></b>
                </td>
                <td>{!!$website_domain->status!!}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection