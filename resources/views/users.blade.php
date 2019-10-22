@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Users List
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Users List</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index ">
                        <i class="fa fa-fw fa-home"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="#"> Users</a>
                </li>
                <li class="active">
                    Users List
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content p-l-r-15">
            <div class="row">
                <div class="panel panel-primary ">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <i class="fa fa-fw fa-users"></i> Users List
                        </h4>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="table">
                                <thead>
                                <tr class="filters">
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>User E-mail</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Crawford</td>
                                    <td>Ondricka</td>
                                    <td>Crawford_Ondricka@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>19 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Jarrell</td>
                                    <td>Hauck</td>
                                    <td>Jarrell.Hauck42@gmail.com</td>
                                    <td>Activated</td>
                                    <td>39 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Ara</td>
                                    <td>Mitchell</td>
                                    <td>Ara.Mitchell@gmail.com</td>
                                    <td>Activated</td>
                                    <td>36 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Sandra</td>
                                    <td>King</td>
                                    <td>Sandra.King51@gmail.com</td>
                                    <td>Activated</td>
                                    <td>29 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Consuelo</td>
                                    <td>Kohler</td>
                                    <td>Consuelo_Kohler77@gmail.com</td>
                                    <td>Activated</td>
                                    <td>47 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Pamela</td>
                                    <td>Cruickshank</td>
                                    <td>Pamela_Cruickshank73@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>50 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Jordan</td>
                                    <td>Lehner</td>
                                    <td>Jordan_Lehner@gmail.com</td>
                                    <td>Activated</td>
                                    <td>47 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Wilhelm</td>
                                    <td>Haley</td>
                                    <td>Wilhelm.Haley@gmail.com</td>
                                    <td>Activated</td>
                                    <td>46 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Julio</td>
                                    <td>Brekke</td>
                                    <td>Julio_Brekke85@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>48 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Margarett</td>
                                    <td>Daniel</td>
                                    <td>Margarett.Daniel@gmail.com</td>
                                    <td>Activated</td>
                                    <td>44 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Alanna</td>
                                    <td>Bayer</td>
                                    <td>Alanna_Bayer98@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>42 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Isabelle</td>
                                    <td>Kilback</td>
                                    <td>Isabelle44@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>48 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Erik</td>
                                    <td>Kessler</td>
                                    <td>Erik58@gmail.com</td>
                                    <td>Activated</td>
                                    <td>43 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Lila</td>
                                    <td>Raynor</td>
                                    <td>Lila.Raynor@gmail.com</td>
                                    <td>Activated</td>
                                    <td>25 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Wallace</td>
                                    <td>Anderson</td>
                                    <td>Wallace_Anderson@gmail.com</td>
                                    <td>Activated</td>
                                    <td>35 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Hester</td>
                                    <td>Schmitt</td>
                                    <td>Hester94@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>19 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Roxanne</td>
                                    <td>Cartwright</td>
                                    <td>Roxanne_Cartwright76@gmail.com</td>
                                    <td>Activated</td>
                                    <td>27 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Lucie</td>
                                    <td>Ondricka</td>
                                    <td>Lucie_Ondricka23@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>46 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Dayna</td>
                                    <td>Crona</td>
                                    <td>Dayna_Crona@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>23 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Monserrat</td>
                                    <td>Ruecker</td>
                                    <td>Monserrat5@gmail.com</td>
                                    <td>Activated</td>
                                    <td>43 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Edgardo</td>
                                    <td>Roob</td>
                                    <td>Edgardo_Roob@gmail.com</td>
                                    <td>Activated</td>
                                    <td>35 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Santino</td>
                                    <td>Blanda</td>
                                    <td>Santino.Blanda46@gmail.com</td>
                                    <td>Activated</td>
                                    <td>26 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Nicola</td>
                                    <td>Rempel</td>
                                    <td>Nicola1@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>5 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Marietta</td>
                                    <td>Strosin</td>
                                    <td>Marietta.Strosin@gmail.com</td>
                                    <td>Activated</td>
                                    <td>6 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Darian</td>
                                    <td>Yost</td>
                                    <td>Darian28@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>20 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Tracy</td>
                                    <td>Blick</td>
                                    <td>Tracy_Blick@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>48 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Lacey</td>
                                    <td>Renner</td>
                                    <td>Lacey_Renner@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>0 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Virgil</td>
                                    <td>Gislason</td>
                                    <td>Virgil_Gislason@gmail.com</td>
                                    <td>Activated</td>
                                    <td>16 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Bryce</td>
                                    <td>Pfannerstill</td>
                                    <td>Bryce.Pfannerstill@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>2 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Tracy</td>
                                    <td>Robel</td>
                                    <td>Tracy_Robel17@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>17 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Nolan</td>
                                    <td>Wintheiser</td>
                                    <td>Nolan_Wintheiser@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>1 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Kacey</td>
                                    <td>Spinka</td>
                                    <td>Kacey23@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>7 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Chyna</td>
                                    <td>Abbott</td>
                                    <td>Chyna_Abbott61@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>40 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Keyon</td>
                                    <td>Lemke</td>
                                    <td>Keyon.Lemke@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>17 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Terrence</td>
                                    <td>Vandervort</td>
                                    <td>Terrence.Vandervort13@gmail.com</td>
                                    <td>Activated</td>
                                    <td>27 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Evie</td>
                                    <td>Pacocha</td>
                                    <td>Evie.Pacocha0@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>30 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Verlie</td>
                                    <td>Hammes</td>
                                    <td>Verlie12@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>38 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Rick</td>
                                    <td>Daugherty</td>
                                    <td>Rick.Daugherty1@gmail.com</td>
                                    <td>Activated</td>
                                    <td>19 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Adan</td>
                                    <td>Cole</td>
                                    <td>Adan_Cole52@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>20 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Llewellyn</td>
                                    <td>Reichel</td>
                                    <td>Llewellyn.Reichel44@gmail.com</td>
                                    <td>Activated</td>
                                    <td>32 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Tessie</td>
                                    <td>Weimann</td>
                                    <td>Tessie1@gmail.com</td>
                                    <td>Activated</td>
                                    <td>22 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Mia</td>
                                    <td>Bayer</td>
                                    <td>Mia.Bayer@gmail.com</td>
                                    <td>Activated</td>
                                    <td>37 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Keanu</td>
                                    <td>Nikolaus</td>
                                    <td>Keanu_Nikolaus2@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>29 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Eduardo</td>
                                    <td>Gibson</td>
                                    <td>Eduardo_Gibson19@gmail.com</td>
                                    <td>Activated</td>
                                    <td>12 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Megane</td>
                                    <td>Block</td>
                                    <td>Megane.Block@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>1 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Bettie</td>
                                    <td>Vandervort</td>
                                    <td>Bettie_Vandervort22@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>41 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Lelah</td>
                                    <td>Dickens</td>
                                    <td>Lelah_Dickens71@gmail.com</td>
                                    <td>Activated</td>
                                    <td>49 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Brook</td>
                                    <td>Orn</td>
                                    <td>Brook_Orn@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>46 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Odessa</td>
                                    <td>Beatty</td>
                                    <td>Odessa.Beatty37@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>50 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Joel</td>
                                    <td>Gerhold</td>
                                    <td>Joel.Gerhold63@gmail.com</td>
                                    <td>Activated</td>
                                    <td>22 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Lloyd</td>
                                    <td>Walsh</td>
                                    <td>Lloyd1@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>30 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Justyn</td>
                                    <td>Dickinson</td>
                                    <td>Justyn97@gmail.com</td>
                                    <td>Activated</td>
                                    <td>31 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Retha</td>
                                    <td>Bartoletti</td>
                                    <td>Retha69@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>7 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Natasha</td>
                                    <td>Krajcik</td>
                                    <td>Natasha_Krajcik74@gmail.com</td>
                                    <td>Activated</td>
                                    <td>15 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Freddie</td>
                                    <td>Dooley</td>
                                    <td>Freddie.Dooley99@gmail.com</td>
                                    <td>Activated</td>
                                    <td>16 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Shawna</td>
                                    <td>Abernathy</td>
                                    <td>Shawna.Abernathy@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>25 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Omer</td>
                                    <td>Breitenberg</td>
                                    <td>Omer_Breitenberg@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>24 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Linwood</td>
                                    <td>DuBuque</td>
                                    <td>Linwood_DuBuque84@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>29 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Ryder</td>
                                    <td>Lockman</td>
                                    <td>Ryder_Lockman10@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>9 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Juvenal</td>
                                    <td>Cole</td>
                                    <td>Juvenal45@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>27 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Hobart</td>
                                    <td>Miller</td>
                                    <td>Hobart20@gmail.com</td>
                                    <td>Activated</td>
                                    <td>33 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Keyshawn</td>
                                    <td>Nolan</td>
                                    <td>Keyshawn_Nolan@gmail.com</td>
                                    <td>Activated</td>
                                    <td>22 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Breanna</td>
                                    <td>Sauer</td>
                                    <td>Breanna_Sauer60@gmail.com</td>
                                    <td>Activated</td>
                                    <td>33 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Jeff</td>
                                    <td>Conn</td>
                                    <td>Jeff_Conn70@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>0 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Reginald</td>
                                    <td>Wilderman</td>
                                    <td>Reginald.Wilderman@gmail.com</td>
                                    <td>Activated</td>
                                    <td>2 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Peggie</td>
                                    <td>Emmerich</td>
                                    <td>Peggie.Emmerich86@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>1 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Kayla</td>
                                    <td>Aufderhar</td>
                                    <td>Kayla_Aufderhar@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>23 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Cara</td>
                                    <td>Hahn</td>
                                    <td>Cara23@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>32 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Turner</td>
                                    <td>Harris</td>
                                    <td>Turner40@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>20 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Florine</td>
                                    <td>Lakin</td>
                                    <td>Florine_Lakin0@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>45 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Adolphus</td>
                                    <td>Swift</td>
                                    <td>Adolphus_Swift61@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>17 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Dovie</td>
                                    <td>Collier</td>
                                    <td>Dovie32@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>14 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Aida</td>
                                    <td>Mante</td>
                                    <td>Aida.Mante24@gmail.com</td>
                                    <td>Activated</td>
                                    <td>7 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Maurine</td>
                                    <td>Gerlach</td>
                                    <td>Maurine_Gerlach15@gmail.com</td>
                                    <td>Activated</td>
                                    <td>24 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Sydney</td>
                                    <td>Nitzsche</td>
                                    <td>Sydney.Nitzsche@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>30 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Nathaniel</td>
                                    <td>Harris</td>
                                    <td>Nathaniel_Harris@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>43 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Clara</td>
                                    <td>Satterfield</td>
                                    <td>Clara.Satterfield55@gmail.com</td>
                                    <td>Activated</td>
                                    <td>27 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Isabell</td>
                                    <td>Kuphal</td>
                                    <td>Isabell_Kuphal15@gmail.com</td>
                                    <td>Activated</td>
                                    <td>7 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Marjory</td>
                                    <td>Pouros</td>
                                    <td>Marjory.Pouros19@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>17 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Winifred</td>
                                    <td>Cummings</td>
                                    <td>Winifred_Cummings@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>38 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Jannie</td>
                                    <td>Rowe</td>
                                    <td>Jannie.Rowe@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>44 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Taryn</td>
                                    <td>Orn</td>
                                    <td>Taryn.Orn84@gmail.com</td>
                                    <td>Activated</td>
                                    <td>35 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Justina</td>
                                    <td>Marks</td>
                                    <td>Justina40@gmail.com</td>
                                    <td>Activated</td>
                                    <td>39 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Bert</td>
                                    <td>Goodwin</td>
                                    <td>Bert.Goodwin67@gmail.com</td>
                                    <td>Activated</td>
                                    <td>31 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Dax</td>
                                    <td>Christiansen</td>
                                    <td>Dax28@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>41 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Jonas</td>
                                    <td>Rowe</td>
                                    <td>Jonas_Rowe12@gmail.com</td>
                                    <td>Activated</td>
                                    <td>43 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Hazle</td>
                                    <td>Pouros</td>
                                    <td>Hazle86@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>37 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Lexus</td>
                                    <td>Raynor</td>
                                    <td>Lexus68@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>13 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Yasmeen</td>
                                    <td>Rippin</td>
                                    <td>Yasmeen_Rippin15@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>43 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Antonina</td>
                                    <td>Howell</td>
                                    <td>Antonina.Howell39@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>36 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Webster</td>
                                    <td>Witting</td>
                                    <td>Webster_Witting@gmail.com</td>
                                    <td>Activated</td>
                                    <td>23 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Ona</td>
                                    <td>Hagenes</td>
                                    <td>Ona_Hagenes5@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>12 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Russ</td>
                                    <td>Purdy</td>
                                    <td>Russ_Purdy41@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>31 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Afton</td>
                                    <td>Fadel</td>
                                    <td>Afton.Fadel84@hotmail.com</td>
                                    <td>Activated</td>
                                    <td>39 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Josie</td>
                                    <td>Stiedemann</td>
                                    <td>Josie.Stiedemann@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>1 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Malachi</td>
                                    <td>Schmitt</td>
                                    <td>Malachi_Schmitt79@gmail.com</td>
                                    <td>Activated</td>
                                    <td>9 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Burley</td>
                                    <td>Zulauf</td>
                                    <td>Burley.Zulauf31@gmail.com</td>
                                    <td>Activated</td>
                                    <td>47 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Jairo</td>
                                    <td>Grimes</td>
                                    <td>Jairo_Grimes@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>42 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Zachariah</td>
                                    <td>Dickinson</td>
                                    <td>Zachariah_Dickinson55@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>36 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Modesta</td>
                                    <td>Herzog</td>
                                    <td>Modesta_Herzog92@yahoo.com</td>
                                    <td>Activated</td>
                                    <td>40 weeks ago</td>
                                    <td><a href="edit_user "><i
                                            class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit User"></i></a><a
                                            href="#"
                                            data-toggle="modal"
                                            data-target="#delete"><i
                                            class="fa fa-fw fa-times text-danger actions_icon" title="Delete User"></i>
                                    </a><a
                                            href="user_profile "><i
                                            class="fa fa-fw fa-star text-success actions_icon"
                                            title="View User"></i></a></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="Heading"
                     aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title custom_align" id="Heading">Delete User</h4>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-warning">
                                    <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to
                                    delete this Account?
                                </div>
                            </div>
                            <div class="modal-footer ">
                                <a href="deleted_users " class="btn btn-danger">
                                    <span class="glyphicon glyphicon-ok-sign"></span> Yes
                                </a>
                                <button type="button" class="btn btn-success" data-dismiss="modal">
                                    <span class="glyphicon glyphicon-remove"></span> No
                                </button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- row-->
            @include('layouts.right_sidebar')
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/jquery.dataTables.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.bootstrap.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/users_custom.js')}}"></script>
<!-- end of page level js -->
@stop