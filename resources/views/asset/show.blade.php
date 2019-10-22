@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show asset
    </h1>
    <br>
    <form method = 'get' action = '{!!url("asset")!!}'>
        <button class = 'btn btn-primary'>asset Index</button>
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
                    <b><i>asset_id : </i></b>
                </td>
                <td>{!!$asset->asset_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>identity_id : </i></b>
                </td>
                <td>{!!$asset->identity_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>identity_table : </i></b>
                </td>
                <td>{!!$asset->identity_table!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>identity_subtype_id : </i></b>
                </td>
                <td>{!!$asset->identity_subtype_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_team_list : </i></b>
                </td>
                <td>{!!$asset->asset_team_list!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_type_id : </i></b>
                </td>
                <td>{!!$asset->asset_type_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>asset_symbol : </i></b>
                </td>
                <td>{!!$asset->asset_symbol!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection