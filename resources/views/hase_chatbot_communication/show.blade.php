@extends('scaffold-interface.layouts.app')
@section('title','Show')
@section('content')

<section class="content">
    <h1>
        Show hase_chatbot_communication
    </h1>
    <br>
    <form method = 'get' action = '{!!url("hase_chatbot_communication")!!}'>
        <button class = 'btn btn-primary'>hase_chatbot_communication Index</button>
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
                    <b><i>communications_id : </i></b>
                </td>
                <td>{!!$hase_chatbot_communication->communications_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>communications_vendor_id : </i></b>
                </td>
                <td>{!!$hase_chatbot_communication->communications_vendor_id!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>communications_topic : </i></b>
                </td>
                <td>{!!$hase_chatbot_communication->communications_topic!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>communications_opcode : </i></b>
                </td>
                <td>{!!$hase_chatbot_communication->communications_opcode!!}</td>
            </tr>
            <tr>
                <td>
                    <b><i>communications_text : </i></b>
                </td>
                <td>{!!$hase_chatbot_communication->communications_text!!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection