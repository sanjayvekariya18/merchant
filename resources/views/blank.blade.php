@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Blank
    @parent
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Blank</h1>
        <ol class="breadcrumb">
            <li>
                <a href="index ">
                    <i class="fa fa-fw fa-home"></i> Dashboard
                </a>
            </li>
            <li> Pages</li>
            <li class="active">
                Blank
            </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content p-l-r-15">
        @include('layouts.right_sidebar')
    </section>
    <!-- /.content -->
@stop